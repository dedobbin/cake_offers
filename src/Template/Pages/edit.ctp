<script>
    $(window).ready(event=>{
        var url = "<?= $this->Url->build('/joboffers/getown/', ['escape' => false, 'fullBase' => true,]);?>";
        sendJson(url, "POST", [], reply=>{
            reply.data.forEach( offer=>{

                var deleteButton = document.createElement('button');
                deleteButton.innerHTML = "delete";
                deleteButton.setAttribute("class", "delete_button");

                var editButton = document.createElement('button');
                editButton.innerHTML = "save";
                editButton.setAttribute("class", "edit_button");

                var element = document.createElement('form');
                element.innerHTML = 
                'Title:<br/> <input type="text" name = title value="'+ offer.title +'"/><br/>' + 
                'Content: <textarea  name = content rows="5">'  + offer.content +'</textarea>' +
                '<hr/>';
                console.log( element.innerHTML);
                element.setAttribute('joboffer_id', offer.id);
                element.append(editButton);
                element.append(deleteButton);
                $('#content').append(element);
                $("form[joboffer_id="+ offer.id +"] .edit_button").on('click', event=>{
                    event.preventDefault();
                    var url = "<?= $this->Url->build('/joboffers/edit/', [
                        'escape' => false,
                        'fullBase' => true,
                    ]);?>" + offer.id;
                    jsonForm(url, $("form[joboffer_id="+ offer.id +"]"));
                });

                $("form[joboffer_id="+ offer.id +"] .delete_button").on('click', event=>{
                    event.preventDefault();
                    event.preventDefault();
                    var url = "<?= $this->Url->build('/joboffers/delete/', [
                        'escape' => false,
                        'fullBase' => true,
                    ]);?>" + offer.id;
                    jsonForm(url, $("form[joboffer_id="+ offer.id +"]"));
                })
            });
        })
    })
</script>

<div>
    <h1>My offers</h1>
    <div id = "content">
    </div>
</div>