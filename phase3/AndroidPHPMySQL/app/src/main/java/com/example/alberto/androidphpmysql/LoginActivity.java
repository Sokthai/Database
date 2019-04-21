package com.example.alberto.androidphpmysql;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
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

public class LoginActivity extends AppCompatActivity{

    private EditText editTextUsername, editTextPassword;
    private Button buttonLogin;
//    private ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);


        editTextUsername = (EditText) findViewById(R.id.editTextUsername);
        editTextPassword = (EditText) findViewById(R.id.editTextPassword);
        buttonLogin = (Button) findViewById(R.id.buttonLogin);


        buttonLogin.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
                userLogin();
            }
        });
    }
    private void userLogin(){
        final String username = editTextUsername.getText().toString().trim();
        final String password = editTextPassword.getText().toString().trim();
        final String url = "http://10.0.0.184:8888/Android/project/controller/login.php";
//        final String url = "http://10.0.0.184:8888/Android/v1/userLogin.php";

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
//                        progressDialog.dismiss();
                        try {
                            JSONObject obj = new JSONObject(response);
//
                            if(!obj.getBoolean("error")){
                                JSONObject objMessage = new JSONObject(obj.getString("message"));
                                SharedPrefManager.getInstance(getApplicationContext())
                                        .userLogin(
                                                objMessage.getInt("id"),
                                                objMessage.getString("name"),
                                                objMessage.getString("email"),
                                                objMessage.getString("phone"),
                                                objMessage.getString("city"),
                                                objMessage.getString("state"),
                                                objMessage.getBoolean("mentee"),
                                                objMessage.getBoolean("mentor"),
                                                objMessage.getString("parent"),
                                                objMessage.getString("role")
                                        );
//                                Intent startIntent = new Intent(getApplicationContext(), WelcomeActivity.class);
                                startActivity(new Intent(getApplicationContext(),WelcomeActivity.class));
                                finish();
//                                startActivity(startIntent);
//                                finish();

                            }else{
                                Toast.makeText(
                                        getApplicationContext(),
                                        obj.getString("message"),
                                        Toast.LENGTH_LONG
                                ).show();
                            }

                            Toast.makeText(getApplicationContext(), "Error : " + obj, Toast.LENGTH_LONG).show();
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
//                        progressDialog.dismiss();

                        Toast.makeText(getApplicationContext(), "sorry some error  " + error.getMessage(), Toast.LENGTH_LONG).show();
                    }
                }
        ){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                 Map<String, String> params = new HashMap<>();
//                 params.put("username", username);
//                 params.put("password", password);

                   params.put("email", username);
                   params.put("password", password);
                 return params;

            }
        };
        Volley.newRequestQueue(this).add(stringRequest);


    }


}
