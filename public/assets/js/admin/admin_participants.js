ajax.get('/api/participants',function(data) {
    data = data.reverse();
    // data.forEach(e=>{
    //     console.log(e.studies)
    //     // debugger
    // })
    gdg = new GrapheneDataGrid(
        {el:'#adminDataGrid',
            name:'participants',
            search:false,columns:false,upload:true,download:true,title:'participants',
            entries:[],
            sortBy:'order',
            actions:actions,
            count:20,
            schema:[
                {name:"id",type:"hidden"},
                {
                    name:"first_name",
                    label:"First Name",
                    type:"text",
                    required:true
                },
                {
                    name:"last_name",
                    label:"Last Name",
                    type:"text",
                    required:true
                },
                {
                    name:"date_of_birth",
                    label:"Date of Birth",
                    type:"date",
                    required:true
                },
                {
                    name:"sex",
                    label:"Sex",
                    type:"select",
                    options: [
                        {
                            label:"Please select",
                        },
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
                    ],
                },
                {
                    name:"gender",
                    label:"Gender",
                    type:"select",
                    options: [
                        {
                            label:"Please select",
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
                    name:"race",
                    label:"Race",
                    type:"select",
                    options: [
                        {
                            label:"Please select",
                        },
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
                    ],
                },
                {
                    name:"ethnicity",
                    label:"Ethnicity",
                    type:"select",
                    options: [
                        {
                            label:"Please select",
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
                },
                {
                    name:"participant_comments",
                    label:"Participant Comments",
                    type:"text"
                },
                {
                    name:"studies",
                    label:"Studies",
                    type:"select",
                    options: [
                        {label:'Please select'},
                        {
                        "path": "/api/studies",
                        "type": "optgroup",
                        "format": {
                            "label":"{{title}}",
                            "value":"{{id}}",
                            "display": "{{title}}"
                            },
                        }
                    ],
                    "array": {
                        "min": 0,
                        "max": 10,
                        "duplicate": {
                            "enable": "auto",
                            "label": "",
                            "clone": false
                        },
                        "remove": {
                            "enable": "auto",
                            "label": ""
                        }
                    },
                    required: true,
                    template: "<ul>{{#attributes.studies}}<li>{{title}}</li>{{/attributes.studies}}</ul>"
                }
            ],
            data:data
    }).on("model:edited",function(grid_event) {
        ajax.put('/api/participants/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        ajax.post('/api/participants',grid_event.model.attributes,function(data) {
            grid_event.model.update(data)
        },function(data) {
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/participants/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    }).on('model:participant_studies',function(grid_event){
        window.location = '/participants/'+grid_event.model.attributes.id+'/studies';
    });;
});
