package com.example.alberto.androidphpmysql;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
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

public class ParentRegistrationActivity extends AppCompatActivity {
    private EditText edtParentEmail, edtParentPassword, edtParentRepeatPassword, edtParentName, edtParentPhone, edtParentCity, edtParentState;
    private CheckBox chkModerator;
    private Button btnRegister;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_parent_registration);

        edtParentEmail = findViewById(R.id.edtParentEmail);
        edtParentPassword = findViewById(R.id.edtParentPassword);
        edtParentRepeatPassword = findViewById(R.id.edtParentRepeatPassword);
        edtParentName = findViewById(R.id.edtParentName);
        edtParentPhone = findViewById(R.id.edtParentPhone);
        edtParentCity = findViewById(R.id.edtParentCity);
        edtParentState = findViewById(R.id.edtParentState);
        chkModerator = findViewById(R.id.chkModerator);

        btnRegister = findViewById(R.id.btnParentRegister);
        btnRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                register();
            }
        });

    }

    private void register(){

        final String email = edtParentEmail.getText().toString().trim();
        final String password = edtParentPassword.getText().toString().trim();
        final String repeatPassword = edtParentRepeatPassword.getText().toString().trim();
        final String username = edtParentName.getText().toString().trim();
        final String phone = edtParentPhone.getText().toString().trim();
        final String city = edtParentCity.getText().toString().trim();
        final String state = edtParentState.getText().toString().trim();
        final String moderator = chkModerator.getText().toString().trim();
        final String role = "parent";

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
                params.put("moderator", moderator);
                return params;
            }
        };

        //RequestHandler.getInstance(this).addToRequestQueue(stringRequest);
        Volley.newRequestQueue(this).add(stringRequest);
    }

}
