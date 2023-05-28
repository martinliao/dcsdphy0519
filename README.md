# ci3rjsphy

## Bootstrap

### 3.4.1

https://getbootstrap.com/docs/3.4/examples/grid/ (https://getbootstrap.com/docs/4.6/examples/grid/)

#myModal.on('hidden.bs.modal', function (e){
    alert();
}

### Modal


|Attributes/Class|Description|
|  ----  | ----  |
|data-keyboard="false"|Prevents modal closing on escape|
|data-backdrop="static"|Prevents modal closing when clicked outside the modal|
|fade|Animates modal|
|modal-sm|Creates a small modal.|
|modal-lg|Creates a large modal.| 



### PHP array to HTML data-* attribute

How php array convert html data tag?

```
$array=array(
    'attr1'=>'value1',
    'id'=>'example',
    'name'=>'john',
    'class'=>'normal'
);
$data = str_replace("=", '="', http_build_query($array, null, '" ', PHP_QUERY_RFC3986)).'"';
echo $data;
```

ref: https://stackoverflow.com/questions/44264249/convert-php-array-into-html-tag-attributes-separated-by-spaces


### jQuery DataTable ajax

```
$('#example').dataTable( {
  "ajax": {
    "url": "data.json",
    "data": function ( d ) {
      return $.extend( {}, d, {
        "extra_search": $('#extra').val()
      } );
    }
  }
} );
```

ref: https://datatables.net/reference/option/ajax.data (from: https://stackoverflow.com/questions/32560635/reload-ajax-request-with-new-parameters)


### Modal scrollable

How prevent BODY from scrolling when a modal is opened
1. Add CSS
2. Add code

```
body.modal-open {
    overflow: hidden;
}
```

```
$("#myModal").on("show", function () {
  $("body").addClass("modal-open");
}).on("hidden", function () {
  $("body").removeClass("modal-open")
});
```

https://stackoverflow.com/questions/9538868/prevent-body-from-scrolling-when-a-modal-is-opened