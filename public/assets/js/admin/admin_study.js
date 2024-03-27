gform.options = {autoFocus:false};
study_form_attributes = [
    {type:"hidden", name:"id", label: 'id'},
    {type:"user", name:"pi_user_id", label: 'PI', required:true},
    {type:"text", name:"title", label:"Title", required:true},
    {type:"textarea", name:"description", label:"Description", required:false},
    {type:"text", name:"location", label:"Location", required:false},
    {type:"date", name:"start_date", label:"Start Date", required:false},
    {type:"date", name:"end_date", label:"End Date", required:false},
];

study_template = `
<div class="row">
    <div class="col-sm-6">
        <!-- Start Column 1 -->

        <!-- Study Info -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#actions}}
                {{#if . == "manage"}}
                    <a class="btn btn-primary btn-xs pull-right" onclick="display_study_info(true)">Manage Study Info</a>
                {{/if}}
            {{/actions}}
            Study Info
        </h3></div>
        <div class="panel-body study-info"></div>
        </div>

        <!-- Study Data Types -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#actions}}
                {{#if . == "manage"}}
                    <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/data_types">Manage Study Data Types</a>
                {{/if}}
            {{/actions}}
            Study Data Types
        </h3></div>
        <div class="panel-body study-data-types">{{>study_data_types_template}}</div>
        </div>

        <!-- End Column 1 -->
    </div>
    <div class='col-sm-6'>

        <!-- Start Column 2 -->

        <!-- Study Participants -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#actions}}
                {{#if . == "manage"}}
                    <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/participants">Manage Study Participants</a>
                {{/if}}
            {{/actions}}
            Study Participants
        </h3></div>
        <div class="panel-body study-participants">{{>study_participants_template}}</div>
        </div>

        <!-- Study Users -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#actions}}
                {{#if . == "manage"}}
                    <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/users">Manage Study Users</a>
                {{/if}}
            {{/actions}}
            Study Users
        </h3></div>
        <div class="panel-body study-users">{{>study_users_template}}</div>
        </div>
        <!-- End Column 2 -->

    </div>
</div>

<style>
.label {
    display:block;
    margin-bottom:5px;
}
.panel-title>a {
    color:white;
}
</style>
`;

study_data_types_template = `
<div style="font-size:20px;">
    {{#data_types}}
        <h4 style="padding-bottom:4px;border:solid;border-width:0px 0px 1px 0px;border-color:#ccc6;text-transform:capitalize;;"
            >{{category}} - {{type}}</h4>
        <h5 style="padding-bottom:20px;;"
            >{{pivot.description}}</h5>
    {{/data_types}}
</div>
{{^data_types}}
    <div class="alert alert-warning">No Study Data Types</div>
{{/data_types}}
`;

study_participants_template = `
<div style="font-size:20px;">
    {{#participants}}
        <a class="label label-default label-block" 
            href="/participants/{{participants[id-1].id}}/studies"
            style="width:100%;padding-top:4px;padding-bottom:4px;"
            >{{first_name}} {{last_name}}</a>
    {{/participants}}
</div>
{{^participants}}
    <div class="alert alert-warning">No Study Participants</div>
{{/participants}}
`;

study_users_template = `
<div style="font-size:20px;">
    {{#users}}
        <a class="label label-default label-block"
        style="width:100%;padding-top:4px;padding-bottom:4px;"
        >{{first_name}} {{last_name}}: <span class="text-capitalize">{{pivot.type}}</span></a>
    {{/users}}
</div>
{{^users}}
    <div class="alert alert-warning">No Study Users</div>
{{/users}}
`;

/* Retrieve study data and display view */
var study_data = {};
var load_study = function() {
    ajax.get('/api/studies/'+id,function(data) {
        study_data = data;
        console.log(study_data);
        data.actions = actions;
        $('#adminDataGrid').html(Ractive({
            el:'study-info',
            template:study_template,
            partials: {
                study_data_types_template:study_data_types_template,
                study_participants_template:study_participants_template,
                study_users_template:study_users_template
            },
            data:data
        }).toHTML());
        display_study_info();
    });
};

/* Display study info as viewable or editable */
var display_study_info = function(edit=false) {
    if(edit == false) {
        manage_actions = [];
    } else {
        manage_actions = [
            {"type":"cancel"},
            {"type":"save"}
        ];
    }
    study_form = new gform({
        "fields":study_form_attributes
            .map(d=>{
                d.edit = edit
                return d;
            }),
        "el":".study-info",
        "data":study_data,
        "actions":manage_actions
    }).on('cancel',function() {
        load_study();
    }).on('save',function(form_event) {
        if(form_event.form.validate()) {
            ajax.put('/api/studies/'+id,form_event.form.get(),function() {
                load_study();
            });
        }
    });
}

/* Verify study id before loading page */
if (id != null && id != '') { //check study model?
    load_study();
};