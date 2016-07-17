package com.example.dell.bloodbank;

import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

public class DonorListFragment extends Fragment {
    /**
     * The fragment argument representing the section number for this
     * fragment.
     */
    private static final String ARG_SECTION_NUMBER = "section_number";


    String type;
    RecyclerView recyclerView;
    DonorCustomAdapter donorCustomAdapter;
    ArrayList<MyDonors> donorList=new ArrayList<>();

    public DonorListFragment() {
    }

    /**
     * Returns a new instance of this fragment for the given section
     * number.
     */
    public static DonorListFragment newInstance(int sectionNumber) {
        DonorListFragment fragment = new DonorListFragment();
        Bundle args = new Bundle();
        switch (sectionNumber)
        {
            case 1:args.putString(ARG_SECTION_NUMBER, "A");
                break;
            case 2:args.putString(ARG_SECTION_NUMBER, "B");
                break;
            case 3:args.putString(ARG_SECTION_NUMBER, "AB");
                break;
            case 4:args.putString(ARG_SECTION_NUMBER, "O");
                break;
        }
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_my_tab, container, false);
        type=getArguments().getString(ARG_SECTION_NUMBER);
        Log.v(getTAG(), "onCreateView"+type);
        Log.v(getTAG(), "onCreateView1");
        ((AppCompatActivity)getActivity()).getSupportActionBar().setTitle("Blood Donors");

        LinearLayoutManager layoutManager=new LinearLayoutManager(getActivity(), LinearLayoutManager.VERTICAL, false);
        Log.v(getTAG(), "onCreateView");
        recyclerView=(RecyclerView)rootView.findViewById(R.id.DonorList);
        Log.v(getTAG(), "onCreateView");
        recyclerView.setLayoutManager(layoutManager);

        getData();

        donorCustomAdapter=new DonorCustomAdapter(donorList, getActivity());

        recyclerView.setAdapter(donorCustomAdapter);
        return rootView;
    }

    public void getData() {
        FetchDonorsTask donorsTask = new FetchDonorsTask();
        donorsTask.execute();
    }

    private String getTAG()
    {
        return MyUtils.TAG;
    }

    ArrayList<MyDonors> getDonorDataFromJson(String donorJsonStr) throws JSONException
    {
        ArrayList<MyDonors> donors = new ArrayList<>();
        final String DONOR_ID = "id";
        final String DONOR_NAME = "name";
        final String DONOR_EMAIL = "email";
        final String DONOR_AGE = "age";
        final String DONOR_GENDER = "gender";
        final String DONOR_CONTACT = "contact";
        final String DONOR_CITY = "city";
        final String DONOR_COUNTRY = "country";
        final String DONOR_TYPE = "type";
        final String DONOR_SIGN = "sign";
        JSONArray donorArray=new JSONArray(donorJsonStr);
        for(int i=0;i<donorArray.length();i++)
        {
            JSONObject obj=donorArray.getJSONObject(i);
            MyDonors donor=new MyDonors();
            donor.setId(obj.getInt(DONOR_ID));
            donor.setName(obj.getString(DONOR_NAME));
            donor.setEmail(obj.getString(DONOR_EMAIL));
            donor.setAge(obj.getInt(DONOR_AGE));
            donor.setGender(obj.getString(DONOR_GENDER));
            donor.setContact(obj.getString(DONOR_CONTACT));
            donor.setCity(obj.getString(DONOR_CITY));
            donor.setCountry(obj.getString(DONOR_COUNTRY));
            donor.setBloodGroup(obj.getString(DONOR_TYPE));
            donor.setSign(obj.getString(DONOR_SIGN).equals("-"));
            donors.add(donor);
        }
        return donors;
    }

    public class FetchDonorsTask extends AsyncTask<Void, Void, ArrayList<MyDonors>> {

        @Override
        protected void onPostExecute(ArrayList<MyDonors> donors) {
            if (donors != null) {
                donorList.clear();
                for (MyDonors o : donors) {
                    Log.v(getTAG(), o.getBloodGroup());
                    if(o.getBloodGroup().equals(type))
                        donorList.add(o);
                    Log.v(getTAG(), o.getName());
                }
                donorCustomAdapter.notifyDataSetChanged();
            }
        }

        @Override
        protected ArrayList<MyDonors> doInBackground(Void... params) {

            HttpURLConnection urlConnection = null;
            BufferedReader reader = null;
            String donorJsonString = null;//"[{\"0\":\"10\",\"id\":\"10\",\"1\":\"Divya\",\"name\":\"Divya\",\"2\":\"divyanarula87@gmail.com\",\"email\":\"divyanarula87@gmail.com\",\"3\":\"29\",\"age\":\"29\",\"4\":\"Female\",\"gender\":\"Female\",\"5\":\"9900414342\",\"contact\":\"9900414342\",\"6\":\"Delhi\",\"city\":\"Delhi\",\"7\":\"India\",\"country\":\"India\",\"8\":\"B\",\"type\":\"B\",\"9\":\"+\",\"sign\":\"+\",\"10\":\"1484642107\",\"expiration\":\"1484642107\"},{\"0\":\"9\",\"id\":\"9\",\"1\":\"Mayank\",\"name\":\"Mayank\",\"2\":\"mayanknarula96@gmail.com\",\"email\":\"mayanknarula96@gmail.com\",\"3\":\"20\",\"age\":\"20\",\"4\":\"Male\",\"gender\":\"Male\",\"5\":\"9582324159\",\"contact\":\"9582324159\",\"6\":\"Delhi\",\"city\":\"Delhi\",\"7\":\"India\",\"country\":\"India\",\"8\":\"AB\",\"type\":\"AB\",\"9\":\"+\",\"sign\":\"+\",\"10\":\"1500712423\",\"expiration\":\"1500712423\"}]";
            try {
                String u="http://192.168.0.4/BloodConnect/blood_bank/appdata_sender.php";
                URL url = new URL(u);

                urlConnection = (HttpURLConnection) url.openConnection();
                urlConnection.setRequestMethod("GET");
                urlConnection.connect();

                InputStream inputStream = urlConnection.getInputStream();
                StringBuffer buffer = new StringBuffer();
                if (inputStream == null) {
                    // Nothing to do.
                    Log.v(getTAG(), "inputstream null");
                    return null;
                }
                reader = new BufferedReader(new InputStreamReader(inputStream));

                String line;
                while ((line = reader.readLine()) != null) {
                    // Since it's JSON, adding a newline isn't necessary (it won't affect parsing)
                    // But it does make debugging a *lot* easier if you print out the completed
                    // buffer for debugging.
                    buffer.append(line + "\n");
                }

                if (buffer.length() == 0) {
                    // Stream was empty.  No point in parsing.
                    Log.v(MyUtils.TAG, "Stream empty while fetching data");
                    return null;
                }
                donorJsonString= buffer.toString();
                Log.v(getTAG(), donorJsonString);

            } catch (Exception e) {
                Log.e(getTAG(), "Error getting data!!!!!: " + e.getMessage());
            } finally {
                if (urlConnection != null) {
                    urlConnection.disconnect();
                }
                if (reader != null) {
                    try {
                        reader.close();
                    } catch (final IOException e) {
                        Log.e(getTAG(), "Error closing stream", e);
                    }
                }
            }
            try {
                return getDonorDataFromJson(donorJsonString);
            } catch (JSONException e) {
                Log.e(getTAG(), "Error parsing JSON: " + e.getMessage());
            }
            return null;
        }
    }
}