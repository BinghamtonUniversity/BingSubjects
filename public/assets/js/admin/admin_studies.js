ajax.get('/api/studies',function(data) {
    console.log(data);
    console.log(auth_user_perms);
    console.log(id);

    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'studies',
            search:false,columns:false,upload:false,download:false,title:'studies',
            entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema: [
                {name:"id",type:"hidden"},
                {
                    name:"pi_user_id",
                    label:"PI",
                    type:"user",
                    template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
                },
                {
                    name:"title",
                    label:"Title",
                    type:"text",
                },
                {
                    name:"description",
                    label:"Description",
                    type:"textarea",
                },
                {
                    name:"start_date",
                    label:"Start Date",
                    type:"date",
                },
                {
                    name:"end_date",
                    label:"End Date",
                    type:"date",
                },
                {
                    name:"location",
                    label:"Location",
                    type:"select",
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
                    ]
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
                    ]
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
                    ]
                },
                {
                    name:"participantCount",
                    label:"Participants",
                    type:"number"
                },
                {
                    name:"data_types",
                    label:"Data Types",
                    type:"text", // search is inefficient - look into other methods
                    template:`
                    {{#each attributes.data_types}}
                        <div style="margin-bottom:10px">{{type}}<br>
                            {{#if pivot.description}}
                                <span style="color:#aaa;font-size:12px;display:inline-block;line-height:15px;">{{pivot.description}}</span>
                            {{/pivot.description}}
                        </div>
                    {{/attributes.data_types}}
                    </div>
                    `
                }
            ],
            data:data
        }).on("create",function(grid_event) {
            grid_event.preventDefault();

            // Give dropdown option if user is allowed to assign others to studies
            if(auth_user_perms.includes('manage_studies')) {
                pi_user_field = {
                    name:"pi_user_id",
                    label:"PI",
                    type:"user",
                    template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
                    options:"/api/users",
                    format:{
                        label:"{{first_name}} {{last_name}}",
                        value:"{{id}}",
                        display:"{{first_name}} {{last_name}}" +
                            '<div style="color:#aaa">{{email}}</div>'
                    }
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

            new gform({
                "legend" : "Create Study",
                "fields": [
                    {name:"id",type:"hidden"},
                    pi_user_field,
                    {
                        name:"title",
                        label:"Title",
                        type:"text",
                    },
                    {
                        name:"description",
                        label:"Description",
                        type:"textarea",
                    },
                    {
                        name:"start_date",
                        label:"Start Date",
                        type:"date",
                    },
                    {
                        name:"end_date",
                        label:"End Date",
                        type:"date",
                    },
                    {
                        name:"location",
                        label:"Location",
                        type:"select",
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
                        ]
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
                        ]
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
                        ]
                    }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_data = form_event.form.get();
                    form_event.form.trigger('close');
                    ajax.post('/api/studies/',form_data,function(data) {
                        //refresh page
                        
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            }).modal();
        }).on("edit",function(e) {
            e.preventDefault();
        }).on("model:edit",function(grid_event) {
            window.location = '/studies/'+grid_event.model.attributes.id;
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });
});
