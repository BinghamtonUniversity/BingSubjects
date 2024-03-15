ajax.get('/api/studies',function(data) {
    console.log(data);
    data = data.reverse();
    
    // study_form_attributes = [
    //     {name:"id",type:"hidden"},
    //     {
    //         name:"pi_user_id",
    //         label:"PI",
    //         type:"user",
    //         template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
    //         options:"/api/users",
    //         format: {
    //             label:"{{first_name}} {{last_name}}",
    //             value:"{{id}}",
    //             display:"{{first_name}} {{last_name}}" +
    //                 '<div style="color:#aaa">{{email}}</div>'
    //         }
    //     },
    //     {
    //         name:"start_date",
    //         label:"Start Date",
    //         type:"date",
    //     },
    //     {
    //         name:"end_date",
    //         label:"End Date",
    //         type:"date",
    //     },
    //     {
    //         name:"title",
    //         label:"Title",
    //         type:"text",
    //     },
    //     {
    //         name:"description",
    //         label:"Description",
    //         type:"text",
    //     },
    //     {
    //         name:"location",
    //         label:"Location",
    //         type:"text",
    //     }
    // ];

    // study_form_data_types = [{
    //     name:"data_types",
    //     label:"Data Types",
    //     type:"combobox",
    //     template:`
    //     <ul style="padding-left: 20px">
    //         {{#each attributes.data_types}}
    //             <li>{{type}}: {{description}}</li>
    //         {{/attributes.data_types}}
    //     </ul>
    //     `,
    //     options:"/api/data_types",
    //     format: {
    //         label:"{{type}}",
    //         value:"{{id}}",
    //         display:"{{type}}" +
    //             '<div style="color:#aaa">{{description}}</div>'
    //     }
    // }];

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
                    //options:"/api/users",
                    format: {
                        label:"{{first_name}} {{last_name}}",
                        value:"{{id}}",
                        display:"{{first_name}} {{last_name}}" +
                            '<div style="color:#aaa">{{email}}</div>'
                    }
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
                    name:"location",
                    label:"Location",
                    type:"text",
                },

                // Combobox Array Option
                // {
                //     name:"data_types",
                //     label:"Data Types",
                //     type:"combobox",
                //     template:`
                //     <ul style="padding-left: 20px">
                //         {{#each attributes.data_types}}
                //             <li>{{type}}: {{description}}</li>
                //         {{/attributes.data_types}}
                //     </ul>
                //     `,
                //     options:"/api/data_types",
                //     format: {
                //         label:"{{type}}",
                //         value:"{{id}}",
                //         display:"{{type}}" +
                //             '<div style="color:#aaa">{{description}}</div>'
                //     },
                // },

                // Radio Option
                {
                    name:"data_types",
                    label:"Data Types",
                    type:"combobox",
                    template:`
                    <ul style="padding-left: 20px">
                        {{#each attributes.data_types}}
                            <li><span style="text-transform:capitalize;">{{type}}</span>: {{description}}</li>
                        {{/attributes.data_types}}
                    </ul>
                    `,
                    //options:"/api/data_types",
                    options: [
                        {
                            label:"Assessment",
                            value:"type === 'assessment'", //come back to
                        },
                        {
                            label:"Behavioral",
                            value:"type.behavioral",
                        },
                        {
                            label:"Neurosignal",
                            value:"type.neurosignal",
                        },
                        {
                            label:"Biospecimen",
                            value:"type.biospecimen",
                        }
                    ],
                },

                // {
                //     name:"participants",
                //     label:"Participants",
                //     type:"number",
                //     template:"{{attributes.participants.length}}",
                // },
            ],
            data:data
        }).on("edit",function(e) {
            e.preventDefault();
        }).on("model:edit",function(grid_event) {
            var mymodal = new gform({
                "legend":"Edit",
                "name":"edit",
                "fields": [
                    {name:"id",type:"hidden"},
                    {
                        name:"pi",
                        label:"PI",
                        type:"user",
                        template:"{{first_name}} {{last_name}}",
                        options:"/api/users",
                        format: {
                            label:"{{first_name}} {{last_name}}",
                            value:"{{id}}",
                            display:"{{first_name}} {{last_name}}" +
                                '<div style="color:#aaa">{{email}}</div>'
                        }
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
                        name:"location",
                        label:"Location",
                        type:"text",
                    },
                    // {
                    //     name:"studys_data_types",
                    //     label:"Study's Data Types",
                    //     type:"fieldset",
                    // },

                    // Combobox Array Option
                    {
                        name:"data_types",
                        label:"Data Types",
                        type:"combobox",
                        template:"{{type}}: {{description}}",
                        array:{min:0,max:12},
                        //options:"/api/data_types",
                        format: {
                            label:"{{type}}: {{description}}",
                            value:"{{id}}",
                            display:"{{type}}" +
                                '<div style="color:#aaa">{{description}}</div>',
                        }
                        //Look into updating 'click here to create Data Types' button text                  
                    },

                    // Radio Option
                    // {
                    //     name:"data_types",
                    //     label:false,
                    //     type:"radio",
                    //     multiple:true,
                    //     options: [
                    //         {
                    //             type:"optgroup",
                    //             options:"/api/data_types",
                    //         }
                    //     ],
                    //     format: {
                    //         label:"{{type}}: {{description}}",
                    //         value:"{{id}}",
                    //         display:"{{type}}: {{description}}",
                    //     }
                    // }
                ]
            }).on('save',function(form_event) {
                if(form_event.form.validate()) {
                    form_event.form.trigger('close');
                    grid_event.model.attributes = form_event.form.get();
                    ajax.put('/api/studies/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
                        grid_event.model.update(data)
                    },function(data) {
                        grid_event.model.undo();
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            });
            mymodal.modal().set(grid_event.model.attributes);
            mymodal.modal().set({pi:grid_event.model.attributes.pi.first_name+" "+grid_event.model.attributes.pi.last_name});
            
            // Needs revisiting
            mymodal.modal().set({data_types:grid_event.model.attributes.data_types[0].type});
            console.log(grid_event.model.attributes.data_types[0].type);
            // grid_event.model.attributes.data_types.forEach(data_type => {
            //     mymodal.modal().set({data_types:grid_event.model.attributes.data_types[data_type].type});
            // });

        // }).on("model:edited",function(grid_event) {
        //     ajax.put('/api/studies/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
        //         grid_event.model.update(data)
        //     },function(data) {
        //         grid_event.model.undo();
        //     });

        }).on("model:created",function(grid_event) {
            ajax.post('/api/studies',grid_event.model.attributes,function(data) {
                grid_event.model.update(data)
            },function(data) {
                grid_event.model.undo();
            });
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        }).on('model:study_dashboard',function(grid_event) {
            window.location = '/studies/'+grid_event.model.attributes.id;
        }).on('model:study_participants',function(grid_event) {
            window.location = '/studies/'+grid_event.model.attributes.id+'/participants'; 
        
        // To be removed
        }).on('model:study_data_types',function(grid_event) {
            window.location = '/studies/'+grid_event.model.attributes.id+'/data_types';
        }

    );
});
