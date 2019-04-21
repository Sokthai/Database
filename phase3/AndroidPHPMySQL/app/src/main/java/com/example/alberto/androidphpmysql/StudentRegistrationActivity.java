package com.example.alberto.androidphpmysql;

import android.provider.MediaStore;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class StudentRegistrationActivity extends AppCompatActivity {
    private EditText edtStudentEmail, edtStudentPassword, edtStudentRepeatPassword, edtStudentName, edtStudentPhone, edtStudentCity, edtStudentState;
    private RadioGroup radioButtonGroup;
    private Button btnRegister;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_registration);

        edtStudentEmail = findViewById(R.id.edtEmail);
        edtStudentPassword = findViewById(R.id.edtPassword);
        edtStudentRepeatPassword = findViewById(R.id.edtRepeatPassword);
        edtStudentName = findViewById(R.id.edtName);
        edtStudentPhone = findViewById(R.id.edtPhone);
        edtStudentCity = findViewById(R.id.edtCity);
        edtStudentState = findViewById(R.id.edtState);
        radioButtonGroup = findViewById(R.id.radioGroup3);

        btnRegister = findViewById(R.id.btnStudentRegister);
        btnRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                register();
            }
        });
    }

    private void register(){

        final String email = edtStudentEmail.getText().toString().trim();
        final String password = edtStudentPassword.getText().toString().trim();
        final String repeatPassword = edtStudentRepeatPassword.getText().toString().trim();
        final String username = edtStudentName.getText().toString().trim();
        final String phone = edtStudentPhone.getText().toString().trim();
        final String city = edtStudentCity.getText().toString().trim();
        final String state = edtStudentState.getText().toString().trim();
        final int radioButtonID = radioButtonGroup.getCheckedRadioButtonId();



        final String role = "Student";

//        progressDialog.setMessage("Registering user...");
//        progressDialog.show();
//        final String url = "http://10.0.0.184:8888/Android/v1/registerUser.php";
        final String url = "http://10.0.0.184:8888/Android/project/controller/registration.php";

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
//                        progressDialog.dismiss();
                try {
                    JSONObject jsonObject = new JSONObject(response);

                    Toast.makeText(getApplicationContext(),jsonObject.getString("message"),Toast.LENGTH_LONG).show();
                } catch (JSONException e){
                    e.printStackTrace();
                }
            }
        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
//                        progressDialog.hide();
                        Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
                    }
                })
        {

            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String,String> params = new HashMap<>();
                params.put("email", email);
                params.put("password", password);
                params.put("pw-repeat", repeatPassword);
                params.put("name", username);
                params.put("phone", phone);
                params.put("city", city);
                params.put("state", state);
                params.put("role", role);
//                params.put("mentee", mentee);
                return params;
            }
        };

        //RequestHandler.getInstance(this).addToRequestQueue(stringRequest);
        Volley.newRequestQueue(this).add(stringRequest);
    }
}
