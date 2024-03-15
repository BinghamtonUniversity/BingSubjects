study_template = `
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Info</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="data-types-tab" data-toggle="tab" href="#data-types" role="tab" aria-controls="data-types" aria-selected="false">Data Types</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="participants-tab" data-toggle="tab" href="#participants" role="tab" aria-controls="participants" aria-selected="false">Participants</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false">Users</a>
</li>

</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">{{info_template}}</div>
  <div class="tab-pane fade" id="data-types" role="tabpanel" aria-labelledby="data-types-tab">{{data_types_template}}</div>
  <div class="tab-pane fade" id="participants" role="tabpanel" aria-labelledby="participants-tab">Participants Content</div>
  <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">Users Content</div>
</div>
`;

info_template = `<div class="study-info"></div>`;

// <div class="panel panel-default">
//     <div class="panel-heading"><h3 class="panel-title">
//         {{#actions}}
//             {{#if . == "manage"}}
//                 <a class="btn btn-primary btn-xs pull-right" onclick="display_study_info(true)">Manage Study Info</a>
//             {{/if}}
//         {{/actions}}
//         Study Info
//     </h3></div>
//     <div class="panel-body study-info"></div>
// </div>

// <style>
// .label {
//     display:block;
//     margin-bottom:5px;
// }
// .panel-title>a {
//     color:white;
// }
// </style>
// `;

data_types_template = `
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">
        {{#actions}}
            {{#if . == "manage"}}
                <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/data_types">Manage Study Data Types</a>
            {{/if}}
        {{/actions}}
        Study Data Types
    </h3></div>
    <div class="panel-body study-data-types">{{study_data_types_template}}</div>
</div>
`;

// <div class="row">
//     <div class="col-sm-6">
//         <!-- Start Column 1 -->

//         <!-- Study Info -->
//         <div class="panel panel-default">
//         <div class="panel-heading"><h3 class="panel-title">
//             {{#actions}}
//                 {{#if . == "manage"}}
//                     <a class="btn btn-primary btn-xs pull-right" onclick="display_study_info(true)">Manage Study Info</a>
//                 {{/if}}
//             {{/actions}}
//             Study Info
//         </h3></div>
//         <div class="panel-body study-info"></div>
//         </div>

//         <!-- Study Data Types -->
//         <div class="panel panel-default">
//         <div class="panel-heading"><h3 class="panel-title">
//             {{#actions}}
//                 {{#if . == "manage"}}
//                     <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/data_types">Manage Study Data Types</a>
//                 {{/if}}
//             {{/actions}}
//             Study Data Types
//         </h3></div>
//         <div class="panel-body study-data-types">{{>study_data_types_template}}</div>
//         </div>

//         <!-- End Column 1 -->
//     </div>
//     <div class='col-sm-6'>

//         <!-- Start Column 2 -->

//         <!-- Study Participants -->
//         <div class="panel panel-default">
//         <div class="panel-heading"><h3 class="panel-title">
//             {{#actions}}
//                 {{#if . == "manage"}}
//                     <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/participants">Manage Study Participants</a>
//                 {{/if}}
//             {{/actions}}
//             Study Participants
//         </h3></div>
//         <div class="panel-body study-participants">{{>study_participants_template}}</div>
//         </div>

//         <!-- Study Users -->
//         <div class="panel panel-default">
//         <div class="panel-heading"><h3 class="panel-title">
//             {{#actions}}
//                 {{#if . == "manage"}}
//                     <a class="btn btn-primary btn-xs pull-right" href="/studies/{{id}}/users">Manage Study Users</a>
//                 {{/if}}
//             {{/actions}}
//             Study Users
//         </h3></div>
//         <div class="panel-body study-users">{{>study_users_template}}</div>
//         </div>
//         <!-- End Column 2 -->

//     </div>
// </div>

// <style>
// .label {
//     display:block;
//     margin-bottom:5px;
// }
// .panel-title>a {
//     color:white;
// }
// </style>
// `;

study_data_types_template = `
<div style="font-size:20px;">
    {{#data_types}}
        <h4 style="padding-bottom:4px;border:solid;border-width:0px 0px 1px 0px;border-color:#ccc6;;"
            >{{type}}</h4>
        <h5 style="padding-bottom:20px;;"
            >{{description}}</h5>
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
            >{{first_name}}&nbsp{{last_name}}</a>
    {{/participants}}
</div>
{{^participants}}
    <div class="alert alert-warning">No Study Participants</div>
{{/participants}}
`;

// study_users_template = `
// <div style="font-size:20px;">
//     {{#users}}
//         <a class="label label-warning label-block"
//         href="#"
//         style="width:100%;padding-top:4px;padding-bottom:4px;"
//         >{{first_name}}&nbsp{{last_name}}</a>
//     {{/users}}
// </div>
// {{^users}}
//     <div class="alert alert-warning">No Study Users</div>
// {{/users}}
// `;

study_users_template = `
<div class='table table-responsive'>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th scope='col' class='text-center'>Type</th>
                <th scope='col' class='text-center'>Name</th>
                <th scope='col' class='text-center'>Email</th>
            </tr>
        </thead>
        <tbody class='text-center'>
            {{#users}}
                <tr scope='row'>
                    <td class="text-capitalize">{{pivot.type}}</td>
                    <td>{{first_name}} {{last_name}}</td>
                    <td>{{email}}</td>
                </tr>
            {{/users}}
        </tbody>
    </table>
</div>
`;


gform.options = {autoFocus:false};
study_form_attributes = [
    {type:"hidden", name:"id", label: 'id'},
    {type:"user", name:"pi_user_id", label: 'PI'},
    {type:"text", name:"title", label:"Title", required:true},
    {type:"text", name:"location", label:"Location", required:true},
    {type:"text", name:"description", label:"Description", required:true},
    {type:"date", name:"start_date", label:"Start Date", required:false},
    {type:"date", name:"end_date", label:"End Date", required:false},
    // Could add data types here?
];

/* Retrieve study data and display view */
var study_data = {};
var load_study = function() {
    ajax.get('/api/studies/'+id,function(data) {
        study_data = data;
        console.log(study_data);
        data.actions = actions;
        $('#adminDataGrid').html(Ractive({
            template:study_template,
            partials: {
                info_template:info_template
            },
            //     study_data_types_template:study_data_types_template,
            //     study_participants_template:study_participants_template,
            //     study_users_template:study_users_template,
            // },
            data:data
        }).toHTML());
        display_study_info();
    });
}

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
        //"el":".study-info",
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




$('#myTab[href="#info"]').tab('show');

// $('#myTab a').on('click', function (e) { //[href="info"]
//     e.preventDefault()
//     $(this).tab('show')
// })