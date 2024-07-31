ajax.get('/api/studies',function(data) {
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'studies',
            search:false, upload:false,title:'studies',
            actions:actions,
            count:20,
            schema: [
                {name:"id",label: "ID", type:"text", show: false},
                {
                    name:"pi_user_id",
                    label:"PI",
                    type:"user",
                    template:"{{attributes.pi.first_name}} {{attributes.pi.last_name}}",
                    required: true
                },
                {
                    name:"title",
                    label:"Title",
                    type:"text",
                    required: true
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
                    required: true
                },
                {
                    name:"end_date",
                    label:"End Date",
                    type:"date"
                },
                {
                    name:"location",
                    label:"Location",
                    type:"select",
                    required: true,
                    options:[
                        {
                            label:"Please select",
                        },
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
                    required: true,
                    options:[
                        {
                            label:"Please select",
                        },
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
                            label:"Please select",
                        },
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
                    type:"number",
                    show: false
                },
                {
                    name:"data_types",
                    label:`Data Types`,
                    type:"text",
                    show:false,
                    template:`
                    {{#each attributes.study_data_types}}
                        <div style="margin-bottom:10px">{{data_type.category}} - {{data_type.type}}<br>
                            {{#if data_type.description}}
                                <span style="color:#aaa;font-size:12px;display:inline-block;line-height:15px;">{{description}}</span>
                            {{/description}}
                        </div>
                    {{/attributes.study_data_types}}
                    </div>
                    `
                }
            ],
            data:data
        }).on("model:created",function(grid_event) {
            ajax.post('/api/studies/',grid_event.model.attributes,function(data) {
                grid_event.model.update(data);
            })
        }).on("model:view_study",function(grid_event) {
            window.location = '/studies/'+grid_event.model.attributes.id;
        }).on("model:deleted",function(grid_event) {
            ajax.delete('/api/studies/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
                grid_event.model.undo();
            });
        });
});
