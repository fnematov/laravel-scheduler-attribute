# Laravel Scheduler Attribute

The Laravel Scheduler Attribute package revolutionizes the way you schedule tasks in your Laravel applications. Utilizing the power of PHP 8 attributes, this package allows developers to declaratively schedule tasks with minimal setup, directly within class methods, enhancing the maintainability and clarity of your codebase.

## Features

- **Declarative Scheduling**: Easily schedule tasks within your service classes using PHP 8 attributes.
- **Flexible Timing**: Supports both cron expressions and Laravel's built-in scheduling methods for comprehensive control over task timing.
- **Clean Architecture**: Keep your scheduling logic close to the related business logic for better cohesion.
- **Automatic Discovery**: Automatically discovers and registers scheduled tasks, simplifying the scheduling setup process.

## Installation

Install the package via Composer:

```bash
composer require fnematoc/laravel-scheduler-attribute
```

## Usage

To schedule a task, simply add the `#[ArtisanScheduler]` attribute to the method you want to schedule. You can then specify the timing of the task using a cron expression or one of Laravel's built-in scheduling methods.

```php
use Fnematov\LaravelSchedulerAttribute\ArtisanScheduler;

class ReportService
{
    #[ArtisanScheduler(name: 'report:daily', schedule: 'daily')]
    public function generateDailyReport()
    {
        // Your task logic here
    }
}
```
This example schedules the generateDailyReport method to run daily.

## Contributing
No additional configuration is required to use this package.

## Contributing
Contributions are welcome and will be fully credited. Please submit pull requests to the GitHub repository.

## Support
If you encounter any problems, please submit an issue on our [GitHub page](https://github.com/fnematov/laravel-scheduler-attribute).

## License
The Laravel Scheduler Attribute is open-sourced software licensed under the [MIT LICENCE](https://opensource.org/licenses/MIT).
```