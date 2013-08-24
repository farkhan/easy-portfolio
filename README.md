What is it?
========
A simple and easy portfolio module for PyroCMS, based on Codeigniter framework.
![](https://raw.github.com/farkhan/easy-portfolio/master/img/screenshots/backend_1.jpg)
Requirements
========
* PyroCMS 2.2.x
* FotoramaJS 4.3.0 (Included)
* jQuery v1.10.2 (Included)
* jQuery UI v1.10.3 (Included)
* jQuery File Upload (Included)

Getting Started
========
### Git Clone
* Navigate to `addons/shared_addons/modules`
* Clone this repo `git clone git://github.com/farkhan/easy-portfolio.git easy_portfolio`

### Download
* Download the ZIP file and extract its contents
* Rename the extracted folder to `easy_portfolio` and drop it into `addons/shared_addons/modules` folder

In PyroCMS, navigate to **Addons**, then click **Install**
The module will show up as **Easy Portfolio** under the **Content** menu. To access the module from the front-end, create a new Navigation entry in PyroCMS and assign this module to the entry.

Usage
========
1. First click on **Categories** and create some categories to group your portfolio items into
2. Now you can create Portfolio items and assign them to categories. After you create a Portfolio item, you will be redirected to the Images page where you can upload images for this item
3. Click **Done** when you are finished with adding the images.

URI Structure
=======
The uri is structured as  ```portfolio/<category>/<item>```, where ```category``` and ```item``` are slugs created out of the title of the portfolio item.

Category Page (Front-end)
![](https://raw.github.com/farkhan/easy-portfolio/master/img/screenshots/front_end_1.jpg)

Portfolio Item Page (Front-end)
![](https://raw.github.com/farkhan/easy-portfolio/master/img/screenshots/front_end_2.jpg)

FotoramaJS Documentation
========
http://fotorama.io/customize/

jQuery File Upload Documentation
========
https://github.com/blueimp/jQuery-File-Upload/wiki
