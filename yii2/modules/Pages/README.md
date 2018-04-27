# Pages

Ένα απλοϊκό υποσύστημα για τη δημιουργία σελίδων στατικού κειμένου.
Η αρχική έκδοση υποστηρίζει απλώς 
- τίτλο
- περιεχόμενο 
- και ένα μοναδικό λεκτικό αναγνωριστικό 

# Ενεργοποίηση 

Για ενεργοποίηση πρέπει να προστεθεί στο αρχείο παραμέτρων της εφαρμογής
(`web.php` συνήθως) ρύθμιση για να είναι προσβάσιμο το module, κάτω από 
το κλειδί `modules`:

```php
'modules' => [
    'Pages' => [
        'class' => 'app\modules\Pages\PagesModule',
    ],
],
```

# Επεξεργασία σελίδων 

H διαχείριση σελίδων γίνεται από τα γνωστά urls της μορφής:
`http://site.test/index.php/Pages/page`, `http://site.test/index.php/Pages/page/index` κλπ.

# Άντληση σελίδων 

Παρέχετε βοηθητική στατική μέθοδος `getPageContent` με την οποία μπορεί να
γίνει άντληση του περιεχομένου μιας σελίδας με βάση το μοναδικό λεκτικό
αναγνωριστικό. Για παράδειγμα: 

```php
app\modules\Pages\models\Page::getPageContent('about')
```

Άντληση μπορεί να γίνει και με οποιοδήποτε άλλο τρόπο μέσω ActiveQuery για 
παράδειγμα:

```php
app\modules\Pages\models\Page::findOne($id)->content;
app\modules\Pages\models\Page::find()->andWhere(...)->one()->content;
```

Επίσης υπάρχει action με όνομα display που εμφανίζει τον τίτλο και το 
περιεχόμενο της σελίδας, με παράμετρο είτε το αριθμητικό id είτε 
το μοναδικό λεκτικό αναγνωριστικό. Για παράδειγμα: 

```
http://site.test/index.php/Pages/page/display?id=2
http://site.test/index.php/Pages/page/display?id=about
```
