study_template = `
<!-- Nav Tabs -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item active">
        <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" aria-controls="info" aria-selected="true">Info</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="data-types-tab" data-toggle="tab" href="#data-types" aria-controls="data-types" aria-selected="false">Data Types</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="participants-tab" data-toggle="tab" href="#participants" aria-controls="participants" aria-selected="false">Participants</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" aria-controls="users" aria-selected="false">Users</a>
    </li>
</ul>

<!-- Tab Panes -->
<div class="tab-content">
    <div class="tab-pane active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="panel panel-default" style="margin-top:20px;">
            <div class="panel-heading"><h3 class="panel-title">
                {{#actions}}
                    {{#manage}}
                        <a class="btn btn-primary btn-xs pull-right" style="color:white;" onclick="display_study_info(true)">Edit Info</a>
                    {{/manage}}
                {{/actions}}
                Study Info
            </h3></div>
            <div class="panel-body study_info"></div>
        </div>
    </div>
    <div class="tab-pane" id="data-types" role="tabpanel" aria-labelledby="data-types-tab"><div id="study_data_types" style="margin-top:20px;"></div></div>
    <div class="tab-pane" id="participants" role="tabpanel" aria-labelledby="participants-tab"><div id="study_participants" style="margin-top:20px;"></div></div>
    <div class="tab-pane" id="users" role="tabpanel" aria-labelledby="users-tab"><div id="study_users" style="margin-top:20px;"></div></div>
</div>
`;

gform.options = {autoFocus:false};

// Allow user dropdown options if this user is allowed to assign others to studies
get_pi_options = function(editing) {
    if(auth_user_perms.includes('manage_studies')) {
        pi_user_field = {
            name:"pi_user_id",
            label:"PI",
            type:"user",
            template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
            options:"/api/users",
            editable:editing
        };
    } else {
        pi_user_field = {
            name:"pi_user_id",
            label:"PI",
            type:"user",
            template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
            value:id,
            display:"{{first_name}} {{last_name}}",
            editable:false
        };
    }
    return pi_user_field;
};

get_study_form_attributes = function(editing) {
    return [
        {type:"hidden", name:"id", label:"id"},
        get_pi_options(editing),
        {
            type:"text",
            name:"title",
            label:"Title",
            required:true,
            editable:editing
        },
        {
            type:"textarea",
            name:"description",
            label:"Description",
            required:false,
            editable:editing
        },
        {
            type:"date",
            name:"start_date",
            label:"Start Date",
            required:false,
            editable:editing
        },
        {
            type:"date",
            name:"end_date",
            label:"End Date",
            required:false,
            editable:editing
        },
        {
            type:"select",
            name:"location",
            label:"Location",
            options:[
                {
                    label:"In-person",
                    value:"In-person"
                },
                {
                    label:"Virtual",
                    value:"Virtual"
                },
                {
                    label:"Hybrid",
                    value:"Hybrid"
                }
            ],
            editable:editing
        },
        {
            name:"design",
            label:"Design",
            type:"select",
            options:[
                {
                    label:"Cross-sectional",
                    value:"Cross-sectional"
                },
                {
                    label:"Longitudinal",
                    value:"Longitudinal"
                }
            ],
            editable:editing
        },
        {
            name:"sample_type",
            label:"Sample Type",
            type:"select",
            options:[
                {
                    label:"Neurotypical",
                    value:"Neurotypical"
                },
                {
                    label:"Neurodivergent",
                    value:"Neurodivergent"
                },
                {
                    label:"Neurodiverse",
                    value:"Neurodiverse"
                },
                {
                    label:"Unspecified",
                    value:"Unspecified"
                }
            ],
            editable:editing
        }
    ]
};

var display_study_info = function(edit=false) {
    // Determine which fields are editable
    if(edit == false) {
        edit_info_actions = [];
    } else {
        edit_info_actions = [
            {"type":"cancel"},
            {"type":"save"}
        ];
    }

    new gform({
        "fields":get_study_form_attributes(edit),
        "el":".study_info",
        "data":study_data,
        "actions":edit_info_actions
    }).on('cancel',function() {
        load_study();
    }).on('save',function(form_event) {
        if(form_event.form.validate()) {
            ajax.put('/api/studies/'+id,form_event.form.get(),function() {
                load_study();
            });
        };
    });
};

var study_data = {};
var load_study = function() {
    ajax.get('/api/studies/'+id,function(data) {
        study_data = data;
        data.actions = actions;
        $('#adminDataGrid').html(Ractive({
            template:study_template,
            data:data
        }).toHTML());

        /* Info Tab */
        display_study_info();

        /* Data Types Tab */
        study_data_types_template = new GrapheneDataGrid({
            el:'#study_data_types',
            name:'study_data_types',
            search:false,columns:false,upload:false,download:false,title:'study_data_types',
            entries:[],
            sortBy:'order',
            actions:actions['data_types'],
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {
                    name:"category",
                    type:"select",
                    label:"Category",
                    options:[
                        {
                            label:"Assessment",
                            value:"Assessment",
                        },
                        {
                            label:"Behavioral",
                            value:"Behavioral",
                        },
                        {
                            label:"Neurosignal",
                            value:"Neurosignal",
                        },
                        {
                            label:"Biospecimen",
                            value:"Biospecimen",
                        }
                    ],
                },
                {
                    name:"type",
                    type:"select",
                    label:"Type",
                    options:"/api/data_types",
                    format: {
                        label:"{{type}}",
                        value:"{{type}}"
                    }
                },
                {
                    name:"description",
                    type:"text",
                    label:"Description",
                    template:"{{attributes.pivot.description}}"
                }
            ],
            data:data.data_types
        }).on("create",function(grid_event) {
            grid_event.preventDefault();
            new gform({
                "legend" : "Add Data Type to Study",
                "fields": [
                    {name:"id",type:"hidden"},
                    {
                        name:"data_type_id",
                        label:"Type",
                        type:"user",
                        options:"/api/data_types",
                        format:{
                            label:"{{type}}",
                            value:"{{id}}",
                            display:"{{type}}" +
                                '<div style="color:#aaa">{{category}}</div>'
                        }
                    },
                    {
                        name:"description",
                        type:"text",
                        label:"Description"
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_data = form_event.form.get();
                    form_event.form.trigger('close');
                    ajax.post('/api/studies/'+id+'/data_types/'+form_data.data_type_id,form_data,function(data) {
                        //refresh page
                        // window.location.hash = '#data_types';
                        // window.location.reload(true);
                        //grid_event.model.update(data);
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal();
        }).on("edit",function(e) {
            e.preventDefault();
        }).on("model:edit",function(grid_event) {
            new gform({
                "legend" : "Update Description for this study's "+grid_event.model.attributes.type,
                "fields": [
                    {name:"id",type:"hidden"},
                    {
                        name:"description",
                        type:"text",
                        label:"Description",
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_event.form.trigger('close');
                    form_data = form_event.form.get();
                    ajax.put('/api/studies/'+id+'/data_types/'+form_data.id,form_data,function(data) {
                        grid_event.model.update(data);
                    },function(data) {
                        grid_event.model.undo();
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal().set(grid_event.model.attributes);
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+id+'/data_types/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });

        /* Participants Tab */
        study_participants_template = new GrapheneDataGrid({
            el:'#study_participants',
            name:'study_participants',
            search:false,columns:false,upload:false,download:false,title:'study_participants',
            entries:[],
            sortBy:'order',
            actions:actions['participants'],
            count:20,
            schema:[
                {
                    name:"first_name",
                    label:"First Name",
                    type:"text"
                },
                {
                    name:"last_name",
                    label:"Last Name",
                    type:"text"
                },
                {
                    name:"date_of_birth",
                    label:"Date of Birth",
                    type:"date"
                },
                {
                    name:"sex",
                    label:"Sex",
                    type:"select",
                    options: [
                        {
                            label:"Male",
                            value:"male"
                        },
                        {
                            label:"Female",
                            value:"female"
                        },
                        {
                            label:"Intersex",
                            value:"intersex"
                        }
                    ]
                },
                {
                    name:"gender",
                    label:"Gender",
                    type:"select",
                    options: [
                        {
                            label:"",
                            value:null
                        },
                        {
                            label:"Man",
                            value:"man"
                        },
                        {
                            label:"Woman",
                            value:"woman"
                        },
                        {
                            label:"Non-Binary",
                            value:"non_binary"
                        }
                    ],
                },
                {
                    name:"ethnicity",
                    label:"Ethnicity",
                    type:"select",
                    options: [
                        {
                            label:"",
                            value:null
                        },
                        {
                            label:"Hispanic or Latino",
                            value:"hispanic"
                        },
                        {
                            label:"Not Hispanic or Latino",
                            value:"not_hispanic"
                        }
                    ],
                },
                {
                    name:"race",
                    label:"Race",
                    type:"select",
                    options: [
                        {
                            label:"American Indian or Alaska Native",
                            value:"american_native"
                        },
                        {
                            label:"Asian",
                            value:"asian"
                        },
                        {
                            label:"Black or African American",
                            value:"black"
                        },
                        {
                            label:"Native Hawaiian or Other Pacific Islander",
                            value:"pacific_islander"
                        },
                        {
                            label:"White",
                            value:"white"
                        }
                    ]
                },
                {
                    name:"city_of_birth",
                    label:"City of Birth",
                    type:"text"
                },
                {
                    name:"email",
                    label:"Email",
                    type:"text"
                },
                {
                    name:"phone_number",
                    label:"Phone Number",
                    type:"text"
                }
            ],
            data:data.participants
        }).on("create",function(grid_event) {
            grid_event.preventDefault();
            new gform({
                "legend" : "Add Participant to Study",
                "fields": [                    
                    {name:"id",type:"hidden"},
                    {
                        name:"participant_id",
                        type:"user",
                        label:"Participant",
                        options:"/api/participants",
                        format:{
                            label:"{{first_name}} {{last_name}}",
                            value:"{{id}}",
                            display:"{{first_name}} {{last_name}}" +
                            '<div style="color:#aaa">Date of Birth: {{date_of_birth}}</div>' +
                            '<div style="color:#aaa">Sex: <span class="text-capitalize">{{sex}}</span></div>' +
                            '<div style="color:#aaa">Gender: <span class="text-capitalize">{{gender}}</span></div>' +
                            '<div style="color:#aaa">Race: <span class="text-capitalize">{{race}}</span></div>' +
                            '<div style="color:#aaa">Ethnicity: <span class="text-capitalize">{{ethnicity}}</span></div>' +
                            '<div style="color:#aaa">City of Birth: {{city_of_birth}}</div>' +
                            '<div style="color:#aaa">Email: {{email}}</div>' +
                            '<div style="color:#aaa">Phone Number: {{phone_number}}</div>'
                        }
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_data = form_event.form.get();
                    form_event.form.trigger('close');
                    ajax.post('/api/studies/'+id+'/participants/'+form_data.participant_id,form_data,function(data) {
                        //refresh page
                        //grid_event.model.update(data);
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal();
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+id+'/participants/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });

        /* Users Tab */
        study_users_template = new GrapheneDataGrid({
            el:'#study_users',
            name:'study_users',
            search:false,columns:false,upload:false,download:false,title:'study_users',
            entries:[],
            sortBy:'type',
            actions:actions['users'],
            count:20,
            schema:[
                {name:'id',type:'hidden'},
                {
                    name:'type',
                    type:'select',
                    label:'Type',
                    template:'<span style="text-transform:capitalize">{{attributes.pivot.type}}</span>',
                    options: [
                        {
                            label:"Manager",
                            value:"manager",
                        },
                        {
                            label:"Viewer",
                            value:"viewer",
                        },
                    ]
                },
                {
                    name:"user_id",
                    type:"text",
                    label:"User",
                    template:"{{attributes.first_name}} {{attributes.last_name}} - {{attributes.email}}",
                }
            ],
            data:data.users
        }).on("edit",function(e) {
            e.preventDefault();
        }).on("model:edit",function(grid_event) {
            new gform({
                "legend":"Update "+grid_event.model.attributes.first_name+" "+grid_event.model.attributes.last_name+"'s Permission Type For This Study",
                "name":"update_study_user_type",
                "fields": [
                    {name:'id',type:'hidden'},
                    {
                        name:'type',
                        type:'select',
                        label:'Type',
                        options: [
                            {
                                label:"Manager",
                                value:"manager",
                            },
                            {
                                label:"Viewer",
                                value:"viewer",
                            },
                        ]
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_event.form.trigger('close');
                    grid_event.model.attributes = form_event.form.get();
                    ajax.put('/api/studies/'+id+'/users/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
                        grid_event.model.update(data);
                    },function(data) {
                        grid_event.model.undo();
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal().set(grid_event.model.attributes);
        }).on("create",function(grid_event) {
            grid_event.preventDefault();
            new gform({
                "legend" : "Add User to Study",
                "fields": [
                    {name:'id',type:'hidden'},
                    {
                        name:'type',
                        type:'select',
                        label:'Type',
                        options: [
                            {
                                label:"Manager",
                                value:"manager",
                            },
                            {
                                label:"Viewer",
                                value:"viewer",
                            },
                        ]
                    },
                    {
                        name:"user_id",
                        type:"user",
                        label:"User",
                        template:"{{attributes.first_name}} {{attributes.last_name}} - {{attributes.email}}",
                        options:"/api/users",
                        format: {
                            label:"{{first_name}} {{last_name}}",
                            value:"{{id}}",
                            display:"{{first_name}} {{last_name}}" +
                                '<div style="color:#aaa">{{email}}</div>'
                        }
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_data = form_event.form.get();
                    form_event.form.trigger('close');
                    ajax.post('/api/studies/'+id+'/users/'+form_data.user_id,form_data,function(data) {
                        //refresh page
                        //grid_event.model.update(data);
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal();
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+id+'/users/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });
    });
};

load_study();