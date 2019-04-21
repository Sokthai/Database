package com.example.alberto.androidphpmysql;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.TextView;

public class WelcomeActivity extends AppCompatActivity {
    private TextView txtUsername;
    private TextView txtUserPanel;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_welcome);

        txtUsername = findViewById(R.id.txtUsername);
        txtUserPanel = findViewById(R.id.txtPanel);
        txtUsername.setText(SharedPrefManager.getInstance(this).getUsername().toUpperCase());
        txtUserPanel.setText(SharedPrefManager.getInstance(this).getRole().toUpperCase() + " Panel");

    }
}
