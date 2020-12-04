import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { Observable, Subscription } from 'rxjs';
import { DataService } from 'src/app/services/data-service/data.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  form: FormGroup;
  loginSubscriber: Subscription = null;

  constructor(private formBuilder: FormBuilder,
    private dataService: DataService) { }

  ngOnInit(): void {
    this.form = this.formBuilder.group({
      login: new FormControl(''),
      password: new FormControl('')
    });
  }

  ngOnDestroy(){
    this.loginSubscriber.unsubscribe();
  }

  onSubmit(){
    this.loginSubscriber = this.dataService.Login(this.form.value).subscribe((res:any) => {
      if(res.success)
        alert("Authentification réussie pour " + res.user.Nom + " " + res.user.Prenom);
      else
        alert("Echec");
    });
  }

}
