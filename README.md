# Custom Shipping Method and Email Template Variable

By using this module, we can add one custom shippng method and here we are setting the shipping method code in the email template to send the email for customer baesd on shipping method.

<img width="959" alt="image" src="https://user-images.githubusercontent.com/39663362/191714559-46c0b1ae-c087-4c04-b4de-4d318df1861a.png">

for the same we need to add below code in the email template from the backend

````
{{if order_data.custom_shipping}}
We will notify you when your order is ready for pickup. You can pickup your order at
{{config path="general/store_information/name"}},
{{config path="general/store_information/street_line1"}},
{{config path="general/store_information/city"}},
{{config path="general/store_information/postcode"}},
{{config path="general/store_information/region_id"}},
{{config path="general/store_information/country_id"}}
{{else}}
{{trans "Once your package ships we will send you a tracking number."}}
{{/if}}  
````

And Email tempalte will be 

<img width="959" alt="image" src="https://user-images.githubusercontent.com/39663362/191715405-d1818b63-49c4-4e95-b8f8-0edc88332cb3.png">

