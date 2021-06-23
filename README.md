Combinations
============

Smart algorithms about combinations and generators.

📦 Installation
---------------

It's best to use [Composer](https://getcomposer.org) for installation, and you can also find the package on
[Packagist](https://packagist.org/packages/baraja-core/combinations) and
[GitHub](https://github.com/baraja-core/combinations).

To install, simply use the command:

```shell
$ composer require baraja-core/combinations
```

You can use the package manually by creating an instance of the internal classes, or register a DIC extension to link the services directly to the Nette Framework.

How to use
----------

Expected input:

```
{
    'format': ['M', 'L'],
    'date': ['2020', '2021']
}
```

sample output:

```
[
   {
      'format': 'M',
      'date': '2020'
   },
   {
      'format': 'M',
      'date': '2021'
   },
   {
      'format': 'L',
      'date': '2020'
   },
   {
      'format': 'L',
      'date': '2021'
   }
}
```

📄 License
-----------

`baraja-core/combinations` is licensed under the MIT license. See the [LICENSE](https://github.com/baraja-core/template/blob/master/LICENSE) file for more details.
