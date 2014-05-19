# Blog Plugin

A simple, extensible blogging platform for October CMS.

## Editing posts

The plugin uses the markdown markup for the posts. You can use any Markdown syntax and some special tags for embedding images and videos (requires RainLab Blog Video plugin). To embed an image use the image placeholder:

    ![1](image)

The number in the first part is the placeholder index. If you use multiple images in a post you should use an unique index for each image:

    ![1](image)

    ![2](image)
    
You can also add classes or ids to images by using the [markdown extra](http://michelf.ca/projects/php-markdown/extra/) syntax:

    ![1](image){#id .class}

## Implementing front-end pages

The plugin provides several components for building the post list page (archive), category page, post details page and category list for the sidebar.

### Post list page

Use the `blogPosts` component to display a list of latest blog posts on a page. The component has the following properties:

* **postsPerPage** - how many posts to display on a single page (the pagination is supported automatically). The default value is 10.
* **categoryPage** - path to the category page. The default value is **blog/category** - it matches the pages/blog/category.htm file in the theme directory. This property is used in the default component partial for creating links to the blog categories.
* **postPage** - path to the post details page. The default value is **blog/post** - it matches the pages/blog/post.htm file in the theme directory. This property is used in the default component partial for creating links to the blog posts.
* **noPostsMessage** - message to display in the empty post list.

The blogPosts component injects the following variables to the page where it's used:

* **posts** - a list of blog posts loaded from the database.
* **categoryPage** - contains the value of the `categoryPage` component's property. 
* **postPage** - contains the value of the `postPage` component's property. 
* **noPostsMessage** - contains the value of the `noPostsMessage` component's property. 

The component supports pagination and reads the current page index from the `:page` URL parameter. The next example shows the basic component usage on the blog home page:

    title = "Blog"
    url = "/blog/:page?"

    [blogPosts]
    postsPerPage = "5"
    ==
    {% component 'blogPosts' %}

The post list and the pagination are coded in the default component partial `plugins/rainlab/blog/components/posts/default.htm`. If the default markup is not suitable for your website, feel free to copy it from the default partial and replace the `{% component %}` call in the example above with the partial contents.

### Category page

Use the `blogCategory` component to display a list of a category posts. The component has the following properties:

* **postsPerPage** - how many posts to display on a single page (the pagination is supported automatically). The default value is 10.
* **postPage** - path to the post details page. The default value is **blog/post** - it matches the pages/blog/post.htm file in the theme directory. This property is used in the default component partial for creating links to the blog posts.
* **noPostsMessage** - message to display in the empty post list.
* **paramId** - the URL route parameter used for looking up the category by its slug. The default  value is **slug**.

The blogPosts component injects the following variables to the page where it's used:

* **category** - the blog category object loaded from the database. If the category is not found, the variable value is **null**.
* **postPage** - contains the value of the `postPage` component's property. 
* **posts** - a list of blog posts loaded from the database.

The component supports pagination and reads the current page index from the `:page` URL parameter. The next example shows the basic component usage on the blog category page:

    title = "Blog Category"
    url = "/blog/category/:slug/:page?"

    [blogCategory category]
    ==
    function onEnd()
    {
        // Optional - set the page title to the category name
        if ($this['category'])
            $this->page->title = $this['category']->name;
    }
    ==
    {% if not category %}
        <h2>Category not found</h2>
    {% else %}
        <h2>{{ category.name }}</h2>

        {% component 'category' %}
    {% endif %}

The category post list and the pagination are coded in the default component partial `plugins/rainlab/blog/components/category/default.htm`.

### Post page

Use the `blogPost` component to display a blog post on a page. The component has the following properties:

* **paramId** - the URL route parameter used for looking up the post by its slug. The default value is **slug**.

The component injects the following variables to the page where it's used:

* **blogPost** - the blog post object loaded from the database. If the post is not found, the variable value is **null**.

The next example shows the basic component usage on the blog page:

    title = "Blog Post"
    url = "/blog/post/:slug"

    [blogPost post]
    ==
    <?php
    function onEnd()
    {
        // Optional - set the page title to the post title
        if (isset($this['blogPost']))
            $this->page->title = $this['blogPost']->title;
    }
    ?>
    ==
    {% if not blogPost %}
        <h2>Post not found</h2>
    {% else %}
        <h2>{{ blogPost.title }}</h2>

        {% component 'post' %}
    {% endif %}

The post details is coded in the default component partial `plugins/rainlab/blog/components/post/default.htm`.

### Category list

Use the `blogCategories` component to display a list of blog post categories with links. The component has the following properties:

* **categoryPage** - path to the category page. The default value is **blog/category** - it matches the pages/blog/category.htm file in the theme directory. This property is used in the default component partial for creating links to the blog categories.
* **paramId** - the URL route parameter used for looking up the current category by its slug. The default  value is 
**slug**
* **displayEmpty** - determines if empty categories should be displayed. The default value is false.

The component injects the following variables to the page where it's used:

* **categoryPage** - contains the value of the `categoryPage` component's property. 
* **categories** - a list of blog categories loaded from the database.
* **currentCategorySlug** - slug of the current category. This property is used for marking the current category in the category list.

The component can be used on any page. The next example shows the basic component usage on the blog home page:

    title = "Blog"
    url = "/blog/:page?"

    [blogCategories]
    ==
    ...
    <div class="sidebar">
        {% component 'blogCategories' %}
    </div>
    ...

The category list is coded in the default component partial `plugins/rainlab/blog/components/categories/default.htm`.

## Using markdown

October supports [standard markdown syntax](http://daringfireball.net/projects/markdown/) as well as [extended markdown syntax](http://michelf.ca/projects/php-markdown/extra/)

### Classes and IDs

Classes and IDs can be added to images and other elements as shown below:

```
[link](url){#id .class}
![1](image){#id .class}
# October  {#id .class}
```

### Fenced code blogs

Markdown extra makes it possible to use fenced code blocks. With fenced code blocks you do not need indentation on the areas you want to mark as code:


    ```
    Code goes here
    ```
    
You can also use the `~` symbol:

    ~~~
    Code goes here
    ~~~

### Tables

A *simple* table can be defined as follows:

```
First Header  | Second Header
------------- | -------------
Content Cell  | Content Cell 
Content Cell  | Content Cell 
```

If you want to you can also add a leading and tailing pipe:

```
| First Header  | Second Header |
| ------------- | ------------- |
| Content Cell  | Content Cell  |
| Content Cell  | Content Cell  |
```

To add alignment to the cells you simply need to add a `:` either at the start or end of a separator:

```
| First Header  | Second Header |
| :------------ | ------------: |
| Content Cell  | Content Cell  |
| Content Cell  | Content Cell  |
```

To center align cell just add `:` on both sides:

```
| First Header  | Second Header |
| ------------- | :-----------: |
| Content Cell  | Content Cell  |
| Content Cell  | Content Cell  |
```

### Definition lists

Below is an example of a simple definition list:

```
Laravel
:   A popular PHP framework

October
:   Awesome CMS built on Laravel
```

A term can also have multiple definitions:

```
Laravel
:   A popular PHP framework

October
:   Awesome CMS built on Laravel
:   Supports markdown extra
```

You can also associate more than 1 term to a definition:

```
Laravel
October
:   Built using PHP
```

### Footnotes

With markdown extra it is possible to create reference style footnotes:

```
This is some text with a footnote.[^1]

[^1]: And this is the footnote.
```

### Abbreviations

With markdown extra you can add abbreviations to your markup. The use this functionality first create a definition list:

```
*[HTML]: Hyper Text Markup Language
*[PHP]:  Hypertext Preprocessor
```

Now markdown extra will convert all occurrences of `HTML` and `PHP` as follows:

```
<abbr title="Hyper Text Markup Language">HTML</abbr>
<abbr title="Hypertext Preprocessor">PHP</abbr>
```
