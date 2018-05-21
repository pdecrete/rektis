# Email

Υποστηρικτικό υλικό για ενιαία διαχείριση της αποστολής email από την εφαρμογή.
Περιλαμβάνονται:
- widget για αυτοματοποιημένη δημιουργία κουμπιών αποστολής 
- action για την αποστολή
- controller για την αποστολή 

# Εγκατάσταση και ρυθμίσεις 

Για ενεργοποίηση πρέπει να προστεθεί στο αρχείο παραμέτρων της εφαρμογής
(`web.php` συνήθως) ρύθμιση για να είναι προσβάσιμο το module, κάτω από 
το κλειδί `modules`:

```php
'modules' => [
    'Email' => [
        'class' => 'app\modules\Email\EmailModule',
    ],
],
```

Το πρόσθετο δέχεται ορισμένες παραμέτρους, όπως στο υπόδειγμα 
`config/params-dist.php`. Αντιγράψτε το αρχείο `config/params-dist.php`
στο `config/params.php` και τροποποιήστε σύμφωνα με τις ανάγκες σας.
Παράδειγμα `config/params.php`

```php
return [
    'params' => [
        'from' => [
            'noreply@pdekritis.gr' => 'ΠΔΕ Κρήτης'
        ],
        'replyTo' => 'pdekritisweb@sch.gr'
    ]
];
```

# Widget 

To widget για την αποστολή email εμφανίζει ένα κουμπί αποστολής το οποίο
αποστέλει μέσω φόρμας τα στοιχεία που δίνονται ως παράμετροι. Ένα παράδειγμα 
χρήσης:

```php
<?= EmailButtonWidget::widget([
    'redirect_route' => [
        '/leave/print', 'id' => $model->id
    ],
    'template' => 'leave.mail.main',
    'template_data' => [
        '{DECISION_PROTOCOL}' => $model->decision_protocol,
        '{DECISION_DATE}' => Yii::$app->formatter->asDate($model->decision_protocol_date),
        '{LEAVE_PERSON}' => Yii::$app->params['leavePerson'],
        '{LEAVE_PHONE}' => Yii::$app->params['leavePhone'],
        '{LEAVE_FAX}' => Yii::$app->params['leaveFax'],
    ],
    'files' => [
        LeavePrint::path($filename),
    ],
    'to' => [
        $model->employee->email => $model->employee->fullname
    ],
    'cc' => [
        'pdekritisweb@sch.gr'
    ],
    'label' => 'Στείλε email'
]);
```

Οι παράμετροι που αναγνωρίζονται από το widget και προωθούνται στον controller για την 
αποστολή email παρουσιάζονται στην [ενότητα Controller παρακάτω](#email-controller).

Επιπλέον το widget αναγνωρίζει και την παράμετρο:

- `label`: Αφορά το λεκτικό το οποίο θα εμφανίζεται στο κουμπί αποστολής. 

# Action 

Για την αποστολή email μπορεί να χρησιμοποιηθεί και η μέθοδος 
`\app\modules\Email\controllers\PostmanController::send($data)`
η οποία επιστρέφει τον αριθμό των email που στάλθηκαν. 

# <a name="email-controller"></a>Controller 

Οι παράμετροι που αναγνωρίζονται παρουσιάζονται στην [ενότητα Controller παρακάτω](#email-controller).
είναι:

- `redirect_route`: Route στο οποίο θα γίνει redirect μετά την αποστολή
των email. Το route μπορεί να είναι σε οποιαδήποτε αναγνωρίσιμη από το Url::to
μορφή. _Σημείωση:_ ο σχετικός controller μπορεί θα θέσει flash messages. 
- `template`: Αναγνωριστικό όνομα για το template που θα χρησιμοποιηθεί
για την αποστολή των email. Πρότυπα δημιουργούνται με χρήση του πρόσθετου
`Pages`.
- `template_data`: Πρόκειται για ένα πίνακα αντικαταστάσεων λεκτικών 
στο πρότυπο. Οι αντιστοιχίες αυτές θα εφαρμοστούν στο θέμα και στο κυρίως 
κείμενο των email. 
- `files`: Πίνακας με πλήρη ονόματα (με διαδρομή) των αρχείων που πρέπει
να επισυναφθούν στα email. 
- `from`: Αντικαθιστά τον προεπιλεγμένο αποστολέα του μυνήματος. 
- `to`, `cc`: Διευθύνσεις email για αποστολή. 
- `replyTo`: Αντικαθιστά το προεπιλεγμένο email που αναφέρεται ως διεύθυνση απαντήσεων. 
