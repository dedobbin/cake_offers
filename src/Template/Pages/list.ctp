<script>
    $(window).ready(event=>{
        var url = "joboffers/getall";
        sendJson(url, "POST", [], reply=>{
            reply.data.forEach( author =>{
                author.joboffers.forEach (offer=>{
                    var element = document.createElement('div');
                    element.innerHTML =
                    "<h2>" + offer.title +"</h2>" +
                    "<p>" + offer.content + "</p>" + 
                    "<p>author:<i> " + author.username + "</i></p>"+
                    "<hr/>"
                    $('#content').append(element);
                });
            });
        })
    })
</script>

<div>
    <h1>All current offers</h1>
    <div id = "content"></div>
</div>