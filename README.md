# Combinations

Smart algorithms for generating all possible combinations (Cartesian product) from multiple sets of values.

This PHP library provides a simple, efficient way to generate every possible combination from an associative array where each key contains an array of possible values. Perfect for generating product variants, configuration matrices, test scenarios, and any situation requiring exhaustive combination enumeration.

## ✨ Key Principles

- **Cartesian Product Generation** - Computes all possible combinations from multiple value sets
- **Key Preservation** - Original associative keys are preserved in the output combinations
- **Value Uniqueness Enforcement** - All values across all keys must be unique to ensure unambiguous key mapping
- **Strict Input Validation** - Validates input format before processing to prevent runtime errors
- **Combination Counting** - Ability to count total combinations without generating them (memory efficient)
- **Type Safety** - Strict PHP 8.0+ typing with PHPStan level 8 analysis

## 🏗️ Architecture

The library consists of a single, focused class that handles all combination generation logic:

```
┌─────────────────────────────────────────────────────────────┐
│                   CombinationGenerator                      │
├─────────────────────────────────────────────────────────────┤
│  Public Methods:                                            │
│  ├── generate(array $input): array                          │
│  │   └── Returns all combinations with preserved keys       │
│  └── countCombinations(array $input): int                   │
│      └── Returns total count without generating             │
├─────────────────────────────────────────────────────────────┤
│  Internal:                                                  │
│  ├── combinations() - Recursive Cartesian product algorithm │
│  └── validateInput() - Input format validation              │
└─────────────────────────────────────────────────────────────┘
```

### Algorithm Overview

The generator uses a recursive algorithm to compute the Cartesian product:

1. **Input Validation** - Ensures all keys are non-numeric strings and all values are string arrays
2. **Value-to-Key Mapping** - Creates a reverse lookup map from values to their original keys
3. **Recursive Combination** - Builds combinations from the bottom up using recursion
4. **Key Restoration** - Maps generated combinations back to their original associative keys

## 📦 Installation

It's best to use [Composer](https://getcomposer.org) for installation, and you can also find the package on
[Packagist](https://packagist.org/packages/baraja-core/combinations) and
[GitHub](https://github.com/baraja-core/combinations).

To install, simply use the command:

```shell
$ composer require baraja-core/combinations
```

You can use the package manually by creating an instance of the internal classes, or register a DIC extension to link the services directly to the Nette Framework.

### Requirements

- PHP 8.0 or higher

## 🚀 Basic Usage

### Generating Combinations

```php
use Baraja\Combinations\CombinationGenerator;

$generator = new CombinationGenerator();

$input = [
    'format' => ['M', 'L'],
    'date' => ['2020', '2021'],
];

$combinations = $generator->generate($input);
```

**Input:**

```php
[
    'format' => ['M', 'L'],
    'date' => ['2020', '2021'],
]
```

**Output:**

```php
[
    ['format' => 'M', 'date' => '2020'],
    ['format' => 'M', 'date' => '2021'],
    ['format' => 'L', 'date' => '2020'],
    ['format' => 'L', 'date' => '2021'],
]
```

### Counting Combinations

If you only need to know how many combinations will be generated (e.g., for validation or progress indication), use the `countCombinations()` method:

```php
$generator = new CombinationGenerator();

$input = [
    'size' => ['S', 'M', 'L', 'XL'],
    'color' => ['red', 'blue', 'green'],
    'material' => ['cotton', 'polyester'],
];

$count = $generator->countCombinations($input);
// Returns: 24 (4 × 3 × 2)
```

This method is memory-efficient as it calculates the count without generating the actual combinations.

## 📖 Advanced Examples

### Product Variant Generation

```php
$generator = new CombinationGenerator();

$productOptions = [
    'size' => ['S', 'M', 'L', 'XL'],
    'color' => ['black', 'white', 'navy'],
    'sleeve' => ['short', 'long'],
];

$variants = $generator->generate($productOptions);
// Generates 24 product variants
```

### Test Matrix Generation

```php
$generator = new CombinationGenerator();

$testMatrix = [
    'browser' => ['chrome', 'firefox', 'safari'],
    'os' => ['windows', 'macos', 'linux'],
    'resolution' => ['1080p', '4k'],
];

$testCases = $generator->generate($testMatrix);
// Generates 18 test scenarios
```

### Single Dimension Input

The generator also handles single-dimension inputs correctly:

```php
$generator = new CombinationGenerator();

$input = [
    'status' => ['active', 'inactive', 'pending'],
];

$result = $generator->generate($input);
// Returns: [['status' => 'active'], ['status' => 'inactive'], ['status' => 'pending']]
```

## ⚠️ Input Validation Rules

The generator enforces strict input validation to ensure correct operation:

### 1. Non-Numeric Keys Required

All top-level keys must be non-numeric strings:

```php
// Valid
$input = ['size' => ['S', 'M'], 'color' => ['red', 'blue']];

// Invalid - throws InvalidArgumentException
$input = [0 => ['S', 'M'], 1 => ['red', 'blue']];
$input = ['123' => ['S', 'M']];
```

### 2. Values Must Be Arrays

Each key must contain an array of values:

```php
// Valid
$input = ['size' => ['S', 'M', 'L']];

// Invalid - throws InvalidArgumentException
$input = ['size' => 'M'];
```

### 3. All Values Must Be Strings

Individual values within arrays must be strings:

```php
// Valid
$input = ['year' => ['2020', '2021', '2022']];

// Invalid - throws InvalidArgumentException
$input = ['year' => [2020, 2021, 2022]];
```

### 4. Values Must Be Unique Across All Keys

All values across all keys must be unique. This is required for the reverse key mapping:

```php
// Valid - all values are unique
$input = [
    'size' => ['small', 'medium', 'large'],
    'fit' => ['slim', 'regular', 'relaxed'],
];

// Invalid - 'M' appears in both keys
$input = [
    'size' => ['S', 'M', 'L'],
    'gender' => ['M', 'F'],
];
```

## 🔧 Error Handling

The generator throws `InvalidArgumentException` with descriptive messages for invalid inputs:

```php
try {
    $generator = new CombinationGenerator();
    $combinations = $generator->generate($input);
} catch (\InvalidArgumentException $e) {
    // Handle validation errors
    echo "Invalid input: " . $e->getMessage();
}
```

### Common Error Messages

- `"Section key must be non numeric key."` - A numeric key was used
- `"Section values must be array, but {type} given."` - A non-array value was provided
- `"Section item value must be a string, but {type} given."` - A non-string item in the value array
- `"Value {value} is not unique..."` - Duplicate value found across different keys

## 💡 Use Cases

- **E-commerce**: Generate all product variants (size, color, material combinations)
- **Testing**: Create test matrices for cross-browser/cross-platform testing
- **Configuration**: Enumerate all possible configuration combinations
- **Scheduling**: Generate time slot combinations (day, hour, room)
- **Data Analysis**: Create exhaustive scenario combinations for analysis
- **Form Generation**: Build dynamic form option combinations

## 👤 Author

**Jan Barášek** - [https://baraja.cz](https://baraja.cz)

## 📄 License

`baraja-core/combinations` is licensed under the MIT license. See the [LICENSE](https://github.com/baraja-core/combinations/blob/master/LICENSE) file for more details.
