package com.packtpub.android_php_mysql;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {
    private EditText name, password;
//    private Button btnLogin;
//    private ProgressBar loading;
//    private static String URL_REGISTER = "http://10.0.0.184/android/register.php";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

//        loading = findViewById(R.id.)
        name = findViewById(R.id.edtUsername);
        password = findViewById(R.id.edtPassword);
//        btnLogin = findViewById(R.id.btnLogin);


//        btnLogin.setOnClickListener(new View.OnClickListener(){
//            public void onClick(View v){
//                loginBtn(v);
//            }
//        });
    }

    public void onLogin(View view){
        String user = name.getText().toString();
        String pw = password.getText().toString();
        String type = "login";
        backgroundWorker worker = new backgroundWorker(this);
        worker.execute(type, user, pw);

    }

//    private void regist(){
//        registerBtn.setVisibility(View.GONE);
//        final String name = this.name.getText().toString().trim();
//        final String email = this.email.getText().toString().trim();
//        final String password = this.password.getText().toString().trim();
//
//        StringRequest stringRequest = new StringRequest(Request.Method.POST, URL_REGISTER,
//                new Response.Listener<String>() {
//                    public void onResponse(String response) {
//                        try{
//                            JSONObject jsonObject = new JSONObject(response);
//                            String success = jsonObject.getString("success");
//
//                            if (success.equals("1")){
//                                Toast.makeText(MainActivity.this, "Register Success!", Toast.LENGTH_SHORT).show();
//                            }
//                        }catch (JSONException e){
//                            e.printStackTrace();
//                            Toast.makeText(MainActivity.this, "Register Error!" + e.toString(), Toast.LENGTH_SHORT).show();
//
//                        }
//                    }
//                },
//                new Response.ErrorListener() {
//                    @Override
//                    public void onErrorResponse(VolleyError error) {
//                        Toast.makeText(MainActivity.this, "Register Error!" + error.toString(), Toast.LENGTH_SHORT).show();
//
//                    }
//                })
//
//
//        {
//            @Override
//            protected Map<String, String> getParams() throws AuthFailureError {
//                Map<String, String> params = new HashMap<>();
//                params.put("name", name);
//                params.put("email", email);
//                params.put("password", password);
//                return super.getParams();
//            }
//        };
//
//        RequestQueue requestQueue = Volley.newRequestQueue(this);
//        requestQueue.add(stringRequest);
//
//
//    }
}
