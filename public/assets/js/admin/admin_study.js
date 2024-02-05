// hard coding study id until interface is implemented
var study_id = 1;

var mymodal = new gform(
    {"fields":[
        {name:'output',value:'',type:'output',label:''}
    ],
    "title":"Info",
    "actions":[]}
);

gform.options = {autoFocus:false};
study_form_attributes = [
    {type:"hidden", name:"id", label: 'id'},
    {type:"user", name:"pi_user_id", label: 'PI User', edit:false},
    {type:"text", name:"title", label:"Title", required:true},
    {type:"text", name:"location", label:"Location", required:true},
    {type:"text", name:"description", label:"Description", required:true},
    {type:"date", name:"start_date", label:"Start Date", required:false, help:'Leave blank to define automatically'},
    {type:"date", name:"end_date", label:"End Date", required:false},

    //Add data types here
];

$('#adminDataGrid').html(Ractive({template:`
<div class="row">
    <div class="col-sm-12 study-view">
        <div class="study-template"></div>        
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
`,data:{actions:actions}}).toHTML());

study_template = `
<!-- Study Info -->
<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">
    {{#auth_user_perms}}
        {{#if . == "manage_studies"}}
            <a class="btn btn-primary btn-xs pull-right" href="/identities/{{id}}/accounts">Manage Study Info</a>
        {{/if}}
    {{/auth_user_perms}}
    Study Info
</h3></div>
<div class="panel-body study-info-edit"></div>
</div>

<!-- Study Data Types -->
<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">
    {{#auth_user_perms}}
        {{#if . == "manage_data_types"}}
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
        {{#if . == "manage_participants"}}
            <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/participants">Manage Study Participants</a>
        {{/if}}
    {{/auth_user_perms}}
    Study Participants
</h3></div>
<div class="panel-body study-participants">{{>study_participants_template}}</div>
</div>

<!-- Study Permissions -->
<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title">Study Permissions<a class="btn btn-primary btn-xs pull-right" data-toggle="collapse" href=".study-permissions">Manage Study Permissions</a></h3>
</div>
    <div class="panel-body study-permissions panel-collapse collapse"></div>
</div>
`;

study_data_types_template = `
<div style="font-size:20px;">
    {{#data_types}}
        <a class="label label-default label-block" href="/groups/{{id}}/members">{{data_types[id-1].type}}</a>&nbsp;
    {{/data_types}}
</div>
{{^data_types}}
    <div class="alert alert-warning">No Data Types</div>
{{/data_types}}
`;

study_participants_template = `

`;


app.data.study_id = study_id;
if (study_id != null && study_id != '') {
    ajax.get('/api/studies/'+study_id,function(data) {
        data.auth_user_perms = auth_user_perms;
        window.history.pushState({},'','/studies/'+study_id);
        $('.study-view').show();
        $('.study-template').html(Ractive({
            template:study_template,
            partials: {
                study_data_types_template:study_data_types_template,
                study_participants_template:study_participants_template,
            },
            data:data
        }).toHTML());

        console.log(data);  // Testing

        // Edit Study Info
        study_form = new gform(
            {
            "fields":study_form_attributes,
                // .map(d=>{
                //     if (d.name =='pi_user_id') return d;
                //     d.edit = auth_user_perms.some(e=> {return e === "manage_users"})
                //     return d;
                // }),
            "el":".study-info-edit",
            "data":data,
            }
        ).on('cancel',function(form_event) {
            // Revisit
            if(form_event.form.validate()) {
                form_data = form_event.form.get();
            }

        }).on('save',function(form_event) {
            // Revisit
            if(form_event.form.validate()) {
                form_data = form_event.form.get();
            }
        });
    });
} else {
    $('.study-view').hide();
}

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
