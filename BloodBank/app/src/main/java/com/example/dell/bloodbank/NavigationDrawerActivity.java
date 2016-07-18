package com.example.dell.bloodbank;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.design.widget.NavigationView;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;

import de.hdodenhof.circleimageview.CircleImageView;

public class NavigationDrawerActivity extends AppCompatActivity
        implements NavigationView.OnNavigationItemSelectedListener {

    NavigationView navigationView;
    String name, email, mPath;
    CircleImageView upload;
    TextView userName, userEmail;
    private static final String KEY_NAME="userdetails.UserName",
            KEY_EMAIL="userdetails.UserEmail",
            NULL_VALUE="userdetails.nullValue",
            KEY_IMAGEPATH="userdetails.ProfileImagePath";
    int act=1;
    @Override
    protected void onCreate(Bundle savedInstanceState) {

        Log.v(MyUtils.TAG, "onCreate");
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_navigation_drawer);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.addDrawerListener(toggle);
        toggle.syncState();

        navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
        navigationView.setItemIconTintList(null);

        View hView =  navigationView.getHeaderView(0);
        upload=(CircleImageView) hView.findViewById(R.id.profile_image);
        userName=(TextView)hView.findViewById(R.id.headerName);
        userEmail=(TextView)hView.findViewById(R.id.headerEmail);
        upload.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                UserInput(v);
            }
        });

        getFromSharedPrefs();
        if(mPath!=null)
            loadImageFromStorage();
        Bundle bundle=getIntent().getExtras();
        if(bundle!=null)
        {
            Log.v(MyUtils.TAG, "starting activity from intent");
            updateFromBundle(bundle);
        }
        else
        {
            if(savedInstanceState==null)
                callFragment(new MyTabActivity(), 0);
            else
            {
                act=savedInstanceState.getInt("index");
                switch (act)
                {
                    case 1:
                        callFragment(new MyTabActivity(), 0);
                        break;
                    case 2:
                        callFragment(new RegisterDonor(), 1);
                        break;
                    default:
                        callFragment(new MyTabActivity(), 0);
                        break;
                }
            }
        }

    }

    public boolean updateFromBundle(Bundle bundle)
    {
        Log.v(MyUtils.TAG, String.valueOf(bundle.getInt(MyUtils.ACTIVITY_KEY)));
        switch (bundle.getInt(MyUtils.ACTIVITY_KEY))
        {
            case 1:
                callFragment(new MyTabActivity(), 0);
                break;
            case 2:
                callFragment(new RegisterDonor(), 1);
                break;
            default:
                callFragment(new MyTabActivity(), 0);
                break;
        }
        return true;
    }

    public void getFromSharedPrefs()
    {
        SharedPreferences sharedPref = this.getSharedPreferences(
                getString(R.string.preference_user_key), Context.MODE_PRIVATE);

        name=sharedPref.getString(KEY_NAME, NULL_VALUE);
        if(!(name.equals(NULL_VALUE)))
            userName.setText(name);

        email=sharedPref.getString(KEY_EMAIL, NULL_VALUE);
        if(!(email.equals(NULL_VALUE)))
            userEmail.setText(email);

        mPath=sharedPref.getString(KEY_IMAGEPATH, null);
    }

    private void loadImageFromStorage()
    {
        try {
            File f=new File(mPath);
            Bitmap b = BitmapFactory.decodeStream(new FileInputStream(f));
            upload.setImageBitmap(b);
        }
        catch (FileNotFoundException e)
        {
            e.printStackTrace();
        }
    }


    public void UserInput(View v)
    {
        Intent intent=new Intent(this, UserDetails.class).putExtra("Activity Key", act);
        startActivity(intent);
    }

    @Override
    public void onBackPressed() {
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.navigation_drawer, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    @SuppressWarnings("StatementWithEmptyBody")
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        if (id == R.id.nav_donors) {
            callFragment(new MyTabActivity(), 0);

        } else if (id == R.id.nav_donate) {
            callFragment(new RegisterDonor(), 1);

        } else if (id == R.id.nav_contact) {
            callFragment(new ContactUs(), 2);
        }

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }
    public void callFragment(Fragment fragment, int pos){
        Log.v(MyUtils.TAG, "callFragment: "+ pos);
        act=pos+1;
        FragmentTransaction fragmentTransaction =
                getSupportFragmentManager().beginTransaction();
        fragmentTransaction.replace(R.id.fragment_container, fragment, "fragment"+pos).commit();
        navigationView.getMenu().getItem(pos).setChecked(true);
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        Log.v(MyUtils.TAG, ""+act);
        outState.putInt("index", act);
    }
}
