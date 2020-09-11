$(document).ready(function(){




    //validate identifiers
    var x = $('.row.fieldWrapper').length;
    //add fields for Identifiers
    $("#addSelectors").click(function(){
        var numItems = $('.row.fieldWrapper').length;
        var wrapper = $('div.selectors'); //Input field wrapper
        var fieldHTML = '<div class="row fieldWrapper"><div class="col-md-2"><div class="form-group"><input placeholder="Name" name="selector['+x+'][name]" value="" class="form-control" type="text"></div></div> <div class="col-md-4"><div class="form-group"><input placeholder="Css Selector" name="selector['+x+'][selector]" value="" class="form-control" type="text"></div></div> <div class="col-md-2"><div class="form-group"><select name="selector['+x+'][type]" class="form-control"><option value="_text">text</option><option value="href">link</option></select></div></div> <div class="col-md-2"><div class="form-group"><select name="selector['+x+'][merge]" class="form-control"><option value="N">No</option><option value="Y">Yes</option></select></div></div> <div class="col-md-2"><div class="form-group"><button type="button" class="remove_button btn btn-danger btn-sm">Remove</button></div></div></div>';
        x++;
        $('div.selectors').append(fieldHTML);
    });
    //Once remove button is clicked
    $('div.selectors').on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).closest("div.row").remove();
       // $(this).parent('div.row').remove(); //Remove field html
    });

    $("#preview_btn_link").click(function(){
        var token = $('meta[name="csrf-token"]').attr('content');
        $.post("/links/preview",
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            id: $("input[name=linkCode]").val(),
        },
        function(data, status){
            
            var content = document.createElement('div');
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    var p = document.createElement('p');

                    var b = document.createElement('b');
                    var btext = document.createTextNode(key+' : ');
                    var br = document.createElement('br');
                    var ntext = document.createTextNode(data[key]);
                    b.appendChild(btext);
                    p.appendChild(b);
                    p.appendChild(br);
                    p.appendChild(ntext);
                    content.appendChild(p);
                }
            }
            $("#preview-pane").html(content);
        });
    });

    //side preview-pane domain 
    $("#preview_btn").click(function(){
        var token = $('meta[name="csrf-token"]').attr('content');
       
        $.post("/domains/preview",
        {
            _token:$('meta[name="csrf-token"]').attr('content'),
            url: $("#url").val(),
            pagination_link: $("#pagination_link").val(),
            selector: $("#selector").val(),
            pagination_end: $("#pagination_end").val(),
            pagination_increment_scheme: $("#pagination_increment_scheme").val(),
            
            
        },
        function(data, status){
            
            //Create unordered list of values
            var list = document.createElement('ul');
            data.forEach(function(obj) { 
                obj.forEach(function(entry){
                   
                    var item = document.createElement('li');
                    var a = document.createElement('a');
                    var linkText = document.createTextNode(entry[0]);
                    a.appendChild(linkText);
                    a.title = entry[0];
                    a.href = entry[1];
                    item.appendChild(a);
                    list.appendChild(item);
                })
            });
            //Add to preview pane
            $("#preview-pane").html(list);
          
        });
    }); 

});