package com.example.dell.bloodbank;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;

public class DonorDetailActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_donor_detail);
        MyDonors donor=getIntent().getExtras().getParcelable(MyUtils.PARCELABEL_KEY);
        assert donor!=null;

        TextView tName=(TextView)findViewById(R.id.detail_name);
        TextView tType=(TextView)findViewById(R.id.detail_type);
        TextView tCountry=(TextView)findViewById(R.id.detail_country);
        TextView tCity=(TextView)findViewById(R.id.detail_city);
        TextView tEmail=(TextView)findViewById(R.id.detail_email);
        TextView tAge=(TextView)findViewById(R.id.detail_age);
        TextView tGender=(TextView)findViewById(R.id.detail_gender);
        TextView tContact=(TextView)findViewById(R.id.detail_contact);

        tName.setText(donor.getName());
        String type=donor.getBloodGroup();
        if(donor.isSign())
            type+="-";
        else
            type+="+";
        tType.setText(type);
        tCountry.setText(donor.getCountry());
        tCity.setText(donor.getCity());
        tContact.setText(donor.getContact());
        tAge.setText(String.valueOf(donor.getAge()));
        tEmail.setText(donor.getEmail());
        tGender.setText(donor.getGender());
    }
}
