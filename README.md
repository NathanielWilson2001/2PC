# 2PC
Two Factor Authentication Web Simulation 

Nathaniel Wilson

Developed for course project.

Consists of 3 different websites and 3 databases. Each website represents a company: X, Y and Z. 
X and Y both act as their own services as well as participants. Company Z is a coordinator that works by accessing
products from X and Y and creating transactions between the three. 

This project has 4 main functionalities which are shared by all three 'companies', list available parts, submit purchase orders, search purchase orders, search lines (components within a purchase order) 
Software/Frameworks
  a. The software and frameworks utilized for this assignment include: PHP, HTML,
  CSS, MAMP, phpMyAdmin, cURL and PDO.
  i. HTML and CSS was utilized to create the UI and layout of each website.
  ii. PHP was used for sending data back and forth from the website and
  database, as well as error processing.
  iii. MAMP, phpMyAdmin was used for database and website hosting.
  iv. cURL was used for web scraping and accesses and utilizing Company Y
  and Company X’s restful services for Company Z.
  v. PDO was used to allow for transactions to be used in php for Company Z
  only.
  b. All software was techniques were learned from:
  i. https://www.w3schools.com/
  ii. https://www.php.net/manual/en/ref.curl.php
  iii. https://www.php.net/manual/en/book.pdo.php
  iv. https://www.php.net/manual/en/

- Database structures for X, Y and Z
![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/f7caad4b-0f41-4f0b-87c9-12f6b48826c7)
![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/d3647be1-0e5d-4d85-9c6d-4d5b90dc4417)
![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/02a7d2b4-a68f-4711-a561-8e3cd1bedb25)

- Sample database data
  -   ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/3ef53b2b-eb48-4156-afbb-2d8a672fb98d)
  -   ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/9feea548-b521-437c-a72e-c4d6c3ac7e4e)
  -   ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/eb096adf-5af8-4f05-a22e-343a865abf4b)

- Functionality of Company Z:
   - Main page: ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/dd59fb0e-14cd-432b-a7bc-a710cbc5e749)
   - Available Parts:
        i. After clicking on available parts, the user will immediately receive a view
        of the entire parts table besides quantity on hand, and the if there are
        any parts from both X and Y, the part with the lower price will be
        displayed:
        ii. ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/8215059b-c2c3-4440-bb10-86588da01cfa)
   - Submit Purchase Order:
      i. After selecting the Submit purchase order button the user is brought to a
      simple html form:
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/975a3907-100a-4331-9d30-2d52ae738e36)
      ii. If invalid PO num, Client ID or Number of lines is entered an error message is displayed:
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/cb559e01-586f-4444-beec-2f7902d785e2)
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/4378a9de-467a-4d3f-bd29-f68791c4419f)
      iii. Let’s say we want to create a new purchase order with unique id number ‘20’ for client id ‘3’ with 3 lines. 
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/6b6b32ed-dcb4-47c4-9982-29901c260e7e)
      iv. The user will then be given 3 lines to enter Part ID’s and quantities for. If the quantity is greater than the number on hand none of the lines will go through, changes will roll back and return you to the main page
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/4edca974-c7fe-445b-80b9-2557a94d26e2)
     v. We will order part 1 from company Y, part 2 from company X and part 6 from company Y
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/c2036bb5-99c2-467d-9296-0a6a3acb77f0)
        - Tables for company Z
            - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/4ef7f2ee-a369-4c6f-a286-ca33398f8a3f)
            - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/40559200-eb16-40a4-ac3d-5d3fd92621cd)
        - Tables for company Y
            - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/6e02a7a3-76ac-4f98-96e7-6500edaaaf1b)
            - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/96b677c4-26e9-4ad2-98b5-ac39087a2a18)
        - Tables for company X
            - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/44d6059f-fab8-426d-8bde-a57adadd6128)
         
   - Purchase Orders
      i. When the use selects the purchase order button, they are brought to the purchase order page
         - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/5d33b74a-b252-4d7d-ac38-c1dfcca7349f)
      ii. The user can then enters an invalid input, the following error outputs will appear:
         - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/6b481ca8-6ea2-412b-bdee-f3c8167bfc4d)
      iii. And then a valid input: such as client ID 1
         - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/50fe7583-942d-44f0-aca0-1ed86f5f47b8)
  - Lines
    i. By selecting the lines button, the user will be brought to the lines search page  
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/8e471f3a-93cf-4f28-9669-1fb121d114fd)
    ii. If an invalid input is entered: the following errors may appear
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/c87f56e1-8025-4c96-839c-548f507c63b1)
    iii. Then if we enter valid number such as 5
        - ![image](https://github.com/NathanielWilson2001/2PC/assets/97745329/275ab79f-1f64-4708-a05c-2f116d367412)

  








 


