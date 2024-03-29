<?php

namespace Fnematov\LaravelSchedulerAttribute;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class LaravelSchedulerAttributeProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $this->registerAttributeCommands($schedule);
        });
    }

    protected function registerAttributeCommands(Schedule $schedule): void
    {
        $rootDirectory = App::basePath('app');

        $finder = new Finder();
        $finder->files()->in($rootDirectory)->name('*.php');
        foreach ($finder as $file) {
            $className =  $this->getFullClassNameFromFile($file);
            if (!class_exists($className)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($className);

            if (!$reflectionClass->isInstantiable()) {
                continue; // Skip if class cannot be instantiated
            }

            foreach ($reflectionClass->getMethods() as $method) {
                foreach ($method->getAttributes(ArtisanScheduler::class) as $attribute) {
                    $attributeInstance = $attribute->newInstance();

                    $event = $schedule->call(function () use ($method, $className) {
                        // Use Laravel's container to handle constructor dependencies
                        $instance = App::make($className);
                        // For methods, consider requiring no parameters or only using those with default values
                        $method->invoke($instance);
                    });

                    if (!is_null($attributeInstance->schedule) && method_exists($event, $attributeInstance->schedule)) {
                        call_user_func([$event, $attributeInstance->schedule])->name($attributeInstance->name);
                    } elseif (!is_null($attributeInstance->cron)) {
                        $event->cron($attributeInstance->cron)->name($attributeInstance->name);
                    }
                }
            }
        }
    }

    protected function getFullClassNameFromFile($file): string
    {
        $content = file_get_contents($file->getRealPath());
        $tokens = token_get_all($content);

        $namespace = $fullName = '';
        for ($index = 0; isset($tokens[$index]); $index++) {
            if (!isset($tokens[$index][0])) {
                continue;
            }

            if (T_NAMESPACE === $tokens[$index][0]) {
                $index += 2;
                while (isset($tokens[$index]) && is_array($tokens[$index])) {
                    $namespace .= $tokens[$index++][1];
                }
            }

            if (T_CLASS === $tokens[$index][0]) {
                $index += 2;
                if (isset($tokens[$index][1])) {
                    $fullName = $namespace . '\\' . $tokens[$index][1];
                    break;
                }
            }
        }

        return $fullName;
    }
}