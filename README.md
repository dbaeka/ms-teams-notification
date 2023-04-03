# MS Teams Notification Service 

This package provides sends a notification to MS Teams when the Order Status Changes

## Usage
The package is configured to be used within the main Pets Store.
Check out the [Pets Store Repository](https://github.com/dbaeka/buckhill-pet-commerce) for steps on adding this package as a submodule

Set the `MS_TEAMS_WEBHOOK_URL` value in the `.env` file to set the webhook URL

## Testing
To run tests, simply run
```bash
composer test
```

## Linting
To run the lint test, simply run
```bash
composer pint
```

## PHPStan
To run PHP stan, simply run
```bash
composer analyse
```

## PHPInsight
To run PHP Insight, simply run
```bash
composer insight
```