<!-- The template to display files available for upload -->\

<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}

    <tr class="template-upload fade show">
        <td>


            <span class="preview">
                {% if (file.type =='image/png' || file.type =='image/jpeg' || file.type =='image/gif' ) { %}
                {% } else { %}
                    <i class="fa fa-file" style="font-size:64px;"></i>
                {% } %}

            </span>
       </td>
       <td>
            <p class="name">{%=moment(new Date()).format("YYYY-MM-DD hh:mm A")%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start ml-2" disabled>
                    <i class="fa fa-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}

              <button  type="button" class="btn btn-warning cancel">

                    <i class="fa fa-ban"></i>
                    <span>Cancel</span>
                </button>

            {% } %}
        </td>
    </tr>

{% } %}



</script>
<!-- The template to display files available for download -->

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade show">

        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}"  >{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div class="text-danger"><span >Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span >{%=file.upload_date%}</span>
        </td>
        <td>
            <span >{%=file.type%}</span>
        </td>
        <td>
            <span class="size">{%=file.size%}</span>
        </td>
        <td>
           <span href="" data-toggle="modal"
                 data-target="#DeleteModal" class="btn  btn-primary-outline btnAddMeeting"><i class="fa fa-plus"></i> Add to Meeting
           </span>
        </td>
          <td>
              <a href="{%=file.setDefaultUrl%}" class="btn  btn-secondary boxes">
                 <i class="fa fa-square" ></i>
                 Set as Default
              </a>
        </td>

        <td>
        {% if (file.deleteUrl) { %}
        <button class="btn btn-danger-outline delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
        <i class="fa fa-trash"></i>
        <span>Delete</span>
        </button>


        {% } else { %}
        <button class="btn btn-warning cancel">
        <i class="fa fa-ban"></i>
        <span>Cancel</span>
        </button>
        {% } %}
        </td>
        </tr>
        {% } %}


</script>