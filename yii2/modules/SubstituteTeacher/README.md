# Σημειώσεις 

Για τροφοδότηση στοιχείων για δοκιμές, δώστε την εντολή:

```
./yii2/yii fixture/load "*, -Prefecture" \
    --namespace='app\modules\SubstituteTeacher\dbseed' \
    --templatePath=@app/modules/SubstituteTeacher/dbseed/faker-templates
```

Με χρήση του module [yii2-faker](https://github.com/yiisoft/yii2-faker) γίνεται δημιουργία των δοκιμαστικών
αρχείων ορισμένων στοιχείων (π.χ. στοιχείων καταλόγου αναπλρωτών).

Για δημιουργία αρχείων με δοκιμαστικά δεδομένα 
```
./yii2/yii fixture/generate TeacherRegistry \
    --count=20 \
    --templatePath=@app/modules/SubstituteTeacher/dbseed/faker-templates \
    --fixtureDataPath=@app/modules/SubstituteTeacher/dbseed/data
```

Για τροφοδότηση των στοιχείων για δοκιμές, δώστε την εντολή:

```
./yii2/yii fixture/load "*" \
    --namespace='app\modules\SubstituteTeacher\dbseed' \
    --templatePath=@app/modules/SubstituteTeacher/dbseed 
```


_Εκκρεμεί η τροφοδότηση στοιχείων για όλες τις οντότητες, καθώς και η διαχείριση των Π.Ε._

## Console commands 

Για εκκαθάριση στοιχείων στο σύστημα δοκιμών είναι διαθέσιμες εντολές εκτελέσιμες 
σε περιβάλλον console. 
Για να τις ενεργοποιήσετε προσθέστε στο `config/console.php` της κύριας εφαρμογής
τις ρυθμίσεις: 

```php 
    'bootstrap' => [
        'SubstituteTeacher'
    ],
    'modules' => [
        'SubstituteTeacher' => [ 'class' => 'app\modules\SubstituteTeacher\SubstituteTeacherModule' ],
    ],
```

Οι διαθέσιμες εντολές θα εμφανιστούν στη λίστα εντολών του yii.
Παράδειγμα εκτέλεσης: 

```sh
$ ./yii2/yii SubstituteTeacher/clear
Checking: [=========================================================================] 100% (4/4) ETA: n/a
Check results; entries that would have been deleted:
- 12 audit log entries
- 5 applications marked as deleted
- 0 application positions marked as deleted
- 0 application positions orhpaned (null application)
```
