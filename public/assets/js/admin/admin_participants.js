
ajax.get('/api/participants',function(data) {
    data = data.reverse();
     data.forEach(e=>{
          e.studies = e.studies.map(d=>{
            return (String)(d.id)
        })
        return e
    })

    validate_participants = function(e){
        form_values = e.form.get()
        if (e.form.get('_method')==="create"){
            if (data.filter(d=>{
                return form_values.first_name === d.first_name &&
                    form_values.date_of_birth === d.date_of_birth &&
                    form_values.email === d.email
            }).length > 0){
                return "The participant already exists! Please check the existing participants."
            }
        }else{
            if (data.filter(d=>{
                return form_values.first_name === d.first_name &&
                    form_values.date_of_birth === d.date_of_birth &&
                    form_values.email === d.email &&
                    form_values.id != d.id
            }).length > 0){
                return "The participant already exists! Please check the existing participants."
            }
        }

    }

    gdg = new GrapheneDataGrid(
        {
            el:'#adminDataGrid',
            name:'participants',
            data:data,
            upload:true,download:true,title:'participants',
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
                    type:"email",
                    required: true
                },
                {
                    name:"phone_number",
                    label:"Phone Number",
                    type:"tel"
                },
                {
                    name:"participant_comments",
                    label:"Participant Comments",
                    type:"textarea"
                },
                {
                    type: "combobox",
                    label: "Studies",
                    name: "studies",
                    array: {
                        "min": 1,
                        "max": 25,
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
                    "showColumn": false,
                    "strict": true,
                    "options": [
                        {
                            "label": "",
                            "type": "optgroup",
                            "path": "/api/studies",
                            "format": {
                                "label": "{{title}}",
                                "value": "{{id}}",
                                "display": "{{title}}"
                            },

                        }
                    ],
                    "format": {
                        "label": "{{title}}",
                        "value": "{{id}}",
                        "display": "{{title}}"
                    }
                },
                {
                    name:"created_at",
                    label:"Created At",
                    show:false,
                    type:"date"
                },
                {
                    name:"is_valid",
                    label:"Please type 'confirm' to create",
                    type: 'input',
                    required: true,
                    show: true,
                    validate: [
                        {
                            "type": "custom",
                            "test": validate_participants
                        }
                    ],
                }
            ]

    }).on("model:edited",function(grid_event) {
        ajax.put('/api/participants/'+grid_event.model.attributes.id,grid_event.model.attributes,function(data) {
            data.studies = data.studies.map(d=>{
                return (String)(d.id)
            })
            grid_event.model.update(data)
        },function(data) {
            toastr.error(data.message)
            grid_event.model.undo();
        });
    }).on("model:created",function(grid_event) {
        ajax.post('/api/participants',grid_event.model.attributes,function(data) {
            data.studies = data.studies.map(d=>{
                return (String)(d.id)
            })
            grid_event.model.update(data)
        },function(data) {
            toastr.error(data.message)
            grid_event.model.undo();
        });
    }).on("model:deleted",function(grid_event) {
        ajax.delete('/api/participants/'+grid_event.model.attributes.id,{},function(data) {},function(data) {
            grid_event.model.undo();
        });
    }).on('model:participant_studies',function(grid_event){
        window.location = '/participants/'+grid_event.model.attributes.id+'/studies';
    });
});
