ajax.get('/api/studies',function(data) {
    console.log(data);
    data = data.reverse();
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'studies',
            search:false,columns:false,upload:false,download:false,title:'studies',
            entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {
                    name:"pi_user_id",
                    label:"PI User",
                    type:"user",
                    template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
                    options:"/api/users",
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
                    name:"location",
                    label:"Location",
                    type:"text",
                },
                {
                    name:"description",
                    label:"Description",
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
                    type:"text",
                    // template:`{{#each attributes.data_types}}
                    //     <p> {{type}}: {{description}} <p>
                    // {{/each}}`
                    template:"{{attributes.data_types.0.type}}: {{attributes.data_types.0.description}}",
                    //options:"/api/data_types",
                    // format: {
                    //     label:"{{type}}: {{description}}",
                    //     value:"{{id}}",
                    //     display:"{{type}}: " +
                    //         '<div style="color:#aaa">{{description}}</div>'
                    // }
                        //{type:"optgroup",options: [{label: "N/A",value:null}]},
                        //{type:"optgroup",path: "/api/systems/subsystems",format:{label:"{{system}}: {{subsystem}}", value:"{{subsystem}}"}}
                },
            ],
            data:data
        }).on("model:edited",function(grid_event) {
            ajax.put('/api/studies/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
                grid_event.model.update(data)
            },function(data) {
                grid_event.model.undo();
            });
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
        } 
        // }).on('model:study_data_types',function(grid_event) {
        //     window.location = '/studies/'+grid_event.model.attributes.id+'/data_types';
        // }
    );
});
