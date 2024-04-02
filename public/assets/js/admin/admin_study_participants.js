ajax.get('/api/studies/'+id+'/participants',function(data) {
    data = data.reverse();
    console.log(data);
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'study_participants',
            search:false,columns:false,upload:false,download:false,title:'study_participants',
            entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema:[
                {
                    name:"first_name",
                    label:"First Name",
                    type:"text",
                    template:"{{attributes.participant.first_name}}"
                },
                {
                    name:"last_name",
                    label:"Last Name",
                    type:"text",
                    template:"{{attributes.participant.last_name}}"
                },
                {
                    name:"date_of_birth",
                    label:"Date of Birth",
                    type:"date",
                    template:"{{attributes.participant.date_of_birth}}"
                },
                {
                    name:"sex",
                    label:"Sex",
                    type:"select",
                    template:"{{attributes.participant.sex}}",
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
                    name:"race",
                    label:"Race",
                    type:"select",
                    template:"{{attributes.participant.race}}",
                    options: [
                        {
                            label:"American Indian",
                            value:"american_indian"
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
                    type:"text",
                    template:"{{attributes.participant.city_of_birth}}"
                },
                {
                    name:"email",
                    label:"Email",
                    type:"text",
                    template:"{{attributes.participant.email}}"
                },
                {
                    name:"phone_number",
                    label:"Phone Number",
                    type:"text",
                    template:"{{attributes.participant.phone_number}}"
                }
            ],
            data:data
        }).on("create",function(grid_event) {
            grid_event.preventDefault();
            new gform({
                "legend" : "Add Data Type to Study",
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
                            '<div style="color:#aaa">Race: <span class="text-capitalize">{{race}}</span></div>' +
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
        // }).on("model:created",function(grid_event) {
        //     ajax.post('/api/studies/'+id+'/participants/'+grid_event.model.attributes.participant_id,{},function(data) {
        //         grid_event.model.update(data)
        //     },function(data) {
        //         grid_event.model.undo();
        //     });
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+id+'/participants/'+grid_event.model.attributes.participant_id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });
});
