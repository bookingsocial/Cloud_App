	
	
	var fname = 'test';
	var lname = 'user'; 
	var email = 'test@gmail.com';
	var phone = '9090909090';
	var street = 'testst';
	var city = 'testcity';
	var State = 'teststate';
	var country = 'testcu';
	var postalCode = 'testPc';
	
	var contactEdit = {
				"type": "object",
				"fields": [
				  {
					"fieldname": "First_Name__c",
					"label": "First Name",
					"type": "text",
					"value": fname,
					"validations": [
					  {
						"fieldname": "First_Name__c",
						"required": true,
						"failureMessage": "FirstName required"
					  }
					]
				  },
				  {
					"fieldname": "Last_Name__c",
					"label": "Last Name",
					"type": "text",
					"value": lname,
					"validations": [
					  {
						"fieldname": "Last_Name__c",
						"required": true,
						"failureMessage": "LastName required"
					  }
					]
					
				  },
				 
				  {
					"fieldname": "Email__c",
					"label": "Email",
					"type": "text",
					"value": email,
					"validations": [
					  {
						"fieldname": "Email__c",
						"required": true,
						"failureMessage": "Email required"
					  }
					]
				  },
				  {
					"fieldname": "Phone__c",
					"label": "Phone",
					"type": "text",
					"value": phone,
					"validations": [
					  {
						"fieldname": "Phone__c",
						"required": true,
						"failureMessage": "Phone required"
					  }
					]
					
				  },
				  {
					"fieldname": "MailingStreet",
					"label": "MailingStreet",
					"type": "text",
					"value": street,
					
				  },
				  {
					"fieldname": "MailingCity",
					"label": "MailingCity",
					"type": "text",
					"value": city,
				  },
				  {
					"fieldname": "MailingState",
					"label": "MailingState",
					"type": "text",
					"value": State,
					"validations": [
					  {
						"fieldname": "MailingState",
						
					  }
					]
				  },
				  { 
					"fieldname": "MailingCountry",
					"label": "MailingCountry",
					"type": "text",
					"value": country,
					
				  },
				  {
					"fieldname": "MailingPostalCode",
					"label": "MailingPostalCode",
					"type": "text",
					"value": postalCode,
					"validations": [
					  {
						"fieldname": "MailingPostalCode",
						
					  }
					]
					
				  },
				  {
						"fieldname": "mode",
						"type": "hidden",
						"value": mode
						
				  },
				  {
					"fieldname": "sfId",
					"type": "hidden",
					"value": sfId
					
				  }
				
				],
				
			}
		