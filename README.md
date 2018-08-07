# cms-php

## Sample Code

// Create the client
```
$client = new Client(HOST, SITE, KEY);
```

// Get list of articles
```
$condition = [
    'page' => 1,
    'per' => 30,
];
```
// or by the category
```
$condition = [
    'page'          => '1',
    'category_path' => 'citygas',
    'per'           => 10,
    'sort'          => desc,
];
$articles = $client->getArticles($condition);
```

// Get the article by id
```
$condition = [
    'increment_access_count' => PRODUCTION || \Input::get('increment_access_count', false),
];
$article = $client->getArticleById(ID, $condition);
```

// Get articles by a module
```
$pickup = $client->getArticlesByModule('pickup');
```

// Get the category
```
$client->getCategory('categories/electricity');
```

// Get contents of the category
```
$category_content = $client->getCategoryContent('categories/electricity', ['depth' => 1]);
```
