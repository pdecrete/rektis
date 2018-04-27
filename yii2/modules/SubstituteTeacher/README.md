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
