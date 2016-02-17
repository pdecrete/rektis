#Αρχικές ενέργειες εγκατάστασης

##Προσαρμογές στην εγκατάσταση
- μετακίνηση όλων των φακέλων εκτός του web σε φάκελο με όνομα yii2
- ενημέρωση paths στο αρχείο web/index.php και web/index-test.php 
- ρύθμιση αρχείων `.htaccess` για έλεγχο πρόσβασης

_για το web/.htaccess_
```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php
```

_για το yii2/.htaccess_
```
RewriteEngine on
RewriteRule . - [F]
```

Ενδέχεται να χρειαστεί αλλαγή permissions στους φακέλους
(εδομένου ότι ο ριζικός φάκελος είναι ο '.'):

* ```chgrp -R . www-data``` ή όποιο άλλο group ισχύει
* ```chmod -R g+w yii2/runtime web/assets``` 

Δοκιμάστε εάν η εγκατάσταση ικανοποιεί τις ελάχιστες προϋποθέσεις τρέχοντας το:
```requirements.php```

##Για την εγκατάσταση στο production site 
Να γίνουν comment out ή να σβηστούν τελείως από το αρχείο ```index.php``` οι 
γραμμές που ορίζουν το ```YII_DEBUG``` και το ```YII_ENV```:
```
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');
```

##Ρύθμιση των config αρχείων 
Αντιγράψτε το ```yii2\config\db_sample.php``` στο ```yii2\config\db.php``` και 
κάντε τις απαραίτητες αλλαγές για την εγκατάσταση σας στις τιμές των παραμέτρων
username και password, στο dsn κλπ.

Επίσης κάντε τις απαραίτητες αλλαγές στο αρχείο ```yii2\config\params.php``` 
(ίσως θέλετε να αλλάξετε και το cookieValidationKey στο αρχείο 
```yii2\web.php``` και αλλες τιμες στα αρχεια του φακελου ```yii2\config\```).

##Βάση δεδομένων 
Δημιουργήστε τη βάση δεδομένων

```sql
create database `admappdb` default character set utf8;
```

Δημιουργείστε το σχήμα της βάσης 
```sql
mysql -u root -p admappdb < yii2/data/schema.sql 
```
