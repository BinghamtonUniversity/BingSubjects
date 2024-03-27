ajax.get('/api/studies',function(data) {
    console.log(data);
    data = data.reverse();
    
    // To be removed
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
                    name:"data_types",
                    label:"Data Types",
                    type:"combobox",
                    template:`
                    <ul style="padding-left: 20px">
                        {{#each attributes.data_types}}
                            <li><span style="text-transform:capitalize;">{{category}} - {{type}}</span>: {{pivot.description}}</li>
                        {{/attributes.data_types}}
                    </ul>
                    `,
                    options: [
                        {
                            //come back to
                            label:"Assessment",
                            value:"assessment", 
                        },
                        {
                            label:"Behavioral",
                            value:"behavioral",
                        },
                        {
                            label:"Neurosignal",
                            value:"neurosignal",
                        },
                        {
                            label:"Biospecimen",
                            value:"biospecimen",
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
        }).on("create",function(e) {
            e.preventDefault();
            new gform({
                "legend" : "Create Study",
                "fields": [
                    {name:"id",type:"hidden"},
                    {
                        name:"pi_user_id",
                        label:"PI",
                        type:"user",
                        template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
                        options:"/api/users", //change to current user if not admin
                        format: {
                            label:"{{first_name}} {{last_name}}",
                            value:"{{id}}",
                            display:"{{first_name}} {{last_name}}" +
                                '<div style="color:#aaa">{{email}}</div>'
                        }
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
                ]
            }).modal().on('save',function(form_event) {
                if(form_event.form.validate()){
                    ajax.post('/api/studies',grid_event.model.attributes,function(data) {
                        grid_event.model.update(data)
                    },function(data) {
                        grid_event.model.undo();
                    });
                }
            }).on('cancel',function(form_event) {
                form_event.form.trigger('close');
            });
        
        // }).on("model:created",function(grid_event) {
        //     ajax.post('/api/studies',grid_event.model.attributes,function(data) {
        //         grid_event.model.update(data)
        //     },function(data) {
        //         grid_event.model.undo();
        //     });
        }).on("edit",function(e) {
            e.preventDefault();
        }).on("model:edit",function(grid_event) {
            window.location = '/studies/'+grid_event.model.attributes.id;
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });

         // To be removed
        // }).on('model:study_participants',function(grid_event) {
        //     window.location = '/studies/'+grid_event.model.attributes.id+'/participants'; 
        // }).on('model:study_data_types',function(grid_event) {
        //     window.location = '/studies/'+grid_event.model.attributes.id+'/data_types';
        // }
});
