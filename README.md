# Unfenced

Unleash your fenced code.

An extension for [`league/commonmark`](https://github.com/thephpleague/commonmark/).

## Installation

After installing the package, you will need to register the extension.

### Using `graham-campbell/markdown`

In your `config/markdown.php` file, add the extension somewhere after the `CommonMarkCoreExtension`:

```diff
  return [
      // ...
      'extensions' => [
          League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension::class,
          League\CommonMark\Extension\GithubFlavoredMarkdownExtension::class,
+         Laravel\Unfenced\UnfencedExtension::class,
      ],
  ];
```

### Manually

```php
use Laravel\Unfenced\UnfencedExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

$environment = new Environment();
$environment->addExtension(new CommonMarkCoreExtension());
$environment->addExtension(new UnfencedExtension());

$converter = new MarkdownConverter($environment);
echo $converter->convert('...');
```

## Usage

Features are enabled via the "info" string of the code fence.

> **Note** This extension does not include any CSS.

### Adding file names

To display a file name above your code, add the `filename` attribute:

    ```php filename=src/Hello.php
    // ...
    ```

![image](https://user-images.githubusercontent.com/4977161/181671748-5dee27a6-2ab5-44a1-b787-67dad273ad99.png)

### Adding tabs

Adjacent code fences can be grouped into a tabbed view by specifying the `tab` attribute:

    ```vue tab=Vue
    // ...
    ```

    ```javascript tab=React
    // ...
    ```

![image](https://user-images.githubusercontent.com/4977161/181671943-4edc275c-fd89-4611-bbea-3cefa0c11fad.png)

You may also include the `filename` attribute, which is especially helpful when providing code samples where the file name differs depending on the language:

    ```vue tab=Vue filename=Welcome.vue
    // ...
    ```

    ```javascript tab=React=Welcome.jsx
    // ...
    ```

![image](https://user-images.githubusercontent.com/4977161/181672298-31d54b7e-43a6-4c96-be64-7195d553a9cb.png)

The extension will inject JavaScript into your page when tabs are used. The JavaScript enables the following features:

* It will apply an `active` class to the active tab button and tab content. You may use CSS to highlight the active tab, and hide the inactive tab content.
* If multiple tabbed sections are found, and they contain identical tab names, they will be synchronized. I.e, clicking the "React" tab in one section, will switch to that tab in all sections.
* The active tab is saved to the browsers local storage so that is can persist between pages and even visits.
