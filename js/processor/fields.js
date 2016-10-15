// ------------------------------------------------------------- 
// Filename:		processor/fields.js
// Description:		Creates the text type fields for dynamic forms
// -------------------------------------------------------------
(function() {
    function buildFieldArr(fields) {
        var fieldArr = {};
        for (var pi in fields) {
            fieldArr[fields[pi].fieldname] = fields[pi];
        }
        return fieldArr;
    }
    window.buildFieldArr = buildFieldArr;

    function processor_fields(config, fields, layout) {
        var fieldArr = buildFieldArr(fields);
        $.get(_baseURL + '/layout/objectLayout.mst', function(template) {
            for (var lay in layout) {
                var layer = layout[lay];
                var fieldsToRender = [];
                for (var i = 0, l = layer.fields.length; i < l; i++) {
                    //for(var layfield in layer.fields){
                    //	for(var pi in fields){
                    //		var field = fields[pi];  
                    if (fieldArr.hasOwnProperty(layer.fields[i])) {
                        var field = fieldArr[layer.fields[i]];

                        if (field.type == 'text') {
                            field.type_text = true;
                            //comp_fields_text_init(config, field);
                        } else if (field.type == 'picklist') {
                            for (var p = 0; p < field.pickval[0].length; i++) {
                                if (field.pickval[p].val === field.value)
                                    field.pickval[p].selected = field.value ? "selected" : "";
                            }
                            field.type_picklist = true;
                            //comp_fields_picklist_init(config, field);
                        } else if (field.type == 'textView') {
                           if (field.visibleType == "boolean") {
                                field.isBooleanType = true;
                                if (field.value == 1) {
                                    field.value = true; 
                                } else {
                                    field.value = false;
                                }
                            } else {
                                field.isBooleanType = false;
                            }
                            field.type_textView = true;
                            //comp_fields_textView_init(config, field);
                        } else if (field.type == 'lookup') {
                            field.type_lookup = true;
                            //comp_fields_lookup_init(config, field);
                        } else if (field.type == 'DateTime') {
                            field.type_DateTime = true;
                            //comp_fields_DateTime_init(config, field);
                        } else if (field.type == 'boolean') {
                           
                            field.type_boolean = true;
                            //comp_fields_Checkbox_init(config,field);
                        }
                        /*if(field.hasOwnProperty('validations')){
                        	for(var pf in field.validations){ 
                        		processor_validation_add(field.validations[pf]);
                        	}
                        }*/
                        fieldsToRender.push(field);
                    }
                    //}
                }

                var rendered = Mustache.render(template, {
                    'fields': fieldsToRender,
                    'title': layer.title
                });

                $('#' + config.root).append(rendered);
                for (var k = 0; k < fieldsToRender.length; k++) {
                    var fieldType = fieldsToRender[k];
                    if (fieldType.type_lookup) {
                        $("#" + fieldType.fieldname).select2({
                            ajax: {
                                url: _baseURL + "bksl/ajaxHandler/objectSearch",
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        term: params.term, // search term
                                        object: fieldType.relatedobject,
                                        orgId: config.orgId,
                                        page: params.page
                                    };
                                },
                                processResults: function(data, params) {
                                    // parse the results into the format expected by Select2
                                    // since we are using custom formatting functions we do not need to
                                    // alter the remote JSON data, except to indicate that infinite
                                    // scrolling can be used
                                    params.page = params.page || 1;

                                    return {
                                        results: data.items,
                                        pagination: {
                                            more: (params.page * 20) < data.total_count
                                        }
                                    };
                                },
                                cache: true
                            },
                            minimumInputLength: 2,
                        });
                    } else if (fieldType.type_DateTime) {
                        $('#' + fieldType.fieldname).datepicker();
                    }
                }
            }
        })

    }
    window.processor_fields = processor_fields;

    function processor_listviewHeader(config, dataListView) {

        for (var pi in dataListView) {
            var field = dataListView[pi];
            comp_fields_listviewHeader_init(config, field);

        }
    }
    window.processor_listviewHeader = processor_listviewHeader;

})();

// end of file