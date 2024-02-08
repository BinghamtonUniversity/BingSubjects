// hard coding study id until interface is implemented
var study_id = 1;
console.log("auth user perms:",auth_user_perms); 
// Currently only receiving study permissions for this user/study. May need user permissions as well. 

study_template = `
<div class="row">
    <div class="col-sm-6">
        <!-- Start Column 1 -->

        <!-- Study Info -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#auth_user_perms}}
                {{#if . == "manage_study"}}
                    <a class="btn btn-primary btn-xs pull-right" onclick="display_study_info(true)">Manage Study Info</a>
                {{/if}}
            {{/auth_user_perms}}
            Study Info
        </h3></div>
        <div class="panel-body study-info"></div>
        </div>

        <!-- Study Permissions -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#auth_user_perms}}
                {{#if . == "manage_study"}}
                    <a class="btn btn-primary btn-xs pull-right" data-toggle="collapse" href=".study-permissions">Manage Study Permissions</a>
                {{/if}}
            {{/auth_user_perms}}
            Study Permissions
        </h3></div>
        <div class="panel-body study-permissions panel-collapse collapse"></div>
        </div>
        <!-- End Column 1 -->
    </div>
    <div class='col-sm-6'> 
        <!-- Start Column 2 -->

        <!-- Study Data Types -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#auth_user_perms}}
                {{#if . == "manage_study"}}
                    <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/data_types">Manage Study Data Types</a>
                {{/if}}
            {{/auth_user_perms}}
            Study Data Types
        </h3></div>
        <div class="panel-body study-data-types">{{>study_data_types_template}}</div>
        </div>

        <!-- Study Participants -->
        <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">
            {{#auth_user_perms}}
                {{#if . == "manage_study"}}
                    <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/participants">Manage Study Participants</a>
                {{/if}}
            {{/auth_user_perms}}
            Study Participants
        </h3></div>
        <div class="panel-body study-participants">{{>study_participants_template}}</div>
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
        <h4 style="padding-bottom:4px;border:solid;border-width:0px 0px 1px 0px;border-color:#ccc6;;"
        >{{data_types[id-1].type}}</h4>
        <h5 style="padding-bottom:20px;;"
        >{{data_types[id-1].description}}</h5>
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
        href="/participants/{{participants[id-1].id}}"
        style="width:100%;padding-top:4px;padding-bottom:4px;"
        >{{participants[id-1].first_name}}&nbsp{{participants[id-1].last_name}}</a>
    {{/participants}}
</div>
{{^participants}}
    <div class="alert alert-warning">No Study Participants</div>
{{/participants}}
`;

gform.options = {autoFocus:false};
study_form_attributes = [
    {type:"hidden", name:"id", label: 'id'},
    {type:"user", name:"pi_user_id", label: 'PI User'}, // edit:false},
    {type:"text", name:"title", label:"Title", required:true},
    {type:"text", name:"location", label:"Location", required:true},
    {type:"text", name:"description", label:"Description", required:true},
    {type:"date", name:"start_date", label:"Start Date", required:false},
    {type:"date", name:"end_date", label:"End Date", required:false},

    // Could add data types here?
];

var study_data = {};

// Retrieve study data and display view
var load_study = function(study_id) {
    ajax.get('/api/studies/'+study_id,function(data) {
        study_data = data;
        data.auth_user_perms = auth_user_perms;
        // console.log("data:",data);
        $('#adminDataGrid').html(Ractive({
            template:study_template,
            partials: {
                study_data_types_template:study_data_types_template,
                study_participants_template:study_participants_template,
            },
            data:data
        }).toHTML());

        display_study_info();
    });
}

// Display study info as viewable or editable
var display_study_info = function(edit=false) {
    if(edit == false) {
        manage_actions = []
    } else {
        manage_actions = [
            {"type":"cancel"},
            {"type":"save"}
        ]
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
        load_study(study_id);
    }).on('save',function(form_event) {
        if(form_event.form.validate()) {
            ajax.put('/api/studies/' + study_id, form_event.form.get(), function () {
                load_study(study_id);
            });
        }
    });
}

if (study_id != null && study_id != '') {
    load_study(study_id)
};


    // Edit Study Permissions
    // new gform(
    //     {"fields":[
    //         {
    //             "type": "radio",
    //             "label": "Study Permissions",
    //             "name": "study_permissions",
    //             "multiple": true,
    //             "edit": auth_user_perms.some(e=> {return e === "manage_permissions"}),
    //             "options": [
    //                 {   "label":"View Study",
    //                     "value":"view_study"
    //                 },
    //                 {
    //                     "label": "Manage Study",
    //                     "value": "manage_study"
    //                 }
    //             ]
    //         }
    //     ],
    //     "el":".study-permissions",
    //     "data":{"study_permissions":data.study_permissions},
    //     "actions":[
    //         {
    //             "type": auth_user_perms.some(e=> {return e === "manage_permissions"}) ?"save":"hidden",
    //             "label":"Update Study Permissions","modifiers":"btn btn-primary"}
    //     ]}
    // ).on('save',function(form_event) {
    //     // Update route
    //     ajax.put('/api/identities/'+identity_id+'/permissions',form_event.form.get(),function(data) {});
    // });
