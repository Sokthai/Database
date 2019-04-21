package com.packtpub.phpmysqllogin;

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

public class MainActivity extends AppCompatActivity {

    private EditText username, password;
    private Button btnLogin;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        username = findViewById(R.id.edtName);
        password = findViewById(R.id.edtPassword);
        btnLogin = findViewById(R.id.btnLogin);


        btnLogin.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View view) {
               login();
            }
        });
    }

    public void login(){
        final String name = username.getText().toString().trim();
        final String pw = password.getText().toString().trim();
        final String url = "http://10.0.0.184:8888/Android/v1/userLogin.php";

        StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
//                        progressDialog.dismiss();
                try {
                    JSONObject obj = new JSONObject(response);

//                            if(!obj.getBoolean("error")){
//                                SharedPrefManager.getInstance(getApplicationContext())
//                                        .userLogin(
//                                                obj.getInt("id"),
//                                                obj.getString("username"),
//                                                obj.getString("email")
//                                        );
//                                startActivity(new Intent(getApplicationContext(),ProfileActivity.class));
//                                finish();
//                            }else{
//                                Toast.makeText(
//                                        getApplicationContext(),
//                                        obj.getString("message"),
//                                        Toast.LENGTH_LONG
//                                ).show();
//                            }

                    Toast.makeText(getApplicationContext(), "info is : " + response, Toast.LENGTH_LONG).show();
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
                params.put("username", name);
                params.put("password", pw);
                return params;

            }
        };
        Volley.newRequestQueue(this).add(stringRequest);


    }

}

