package com.example.dell.bloodbank;

import android.app.Activity;
import android.content.Intent;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;

public class DonorCustomAdapter extends RecyclerView.Adapter<DonorCustomAdapter.ViewHolder>{

    ArrayList<MyDonors> donors;
    Activity mContext;
    public DonorCustomAdapter(ArrayList<MyDonors>dataList, Activity context)
    {
        donors=dataList;
        mContext=context;
    }
    public static class ViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener{
        public MyClickHandler handler;
        TextView nameText, contactText, bloodText;

        public ViewHolder(View itemView, MyClickHandler clickHandler) {
            super(itemView);
            handler=clickHandler;

            nameText=(TextView)itemView.findViewById(R.id.donorName);
            contactText=(TextView)itemView.findViewById(R.id.donorContact);
            bloodText=(TextView)itemView.findViewById(R.id.text_blood_type);

            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View v) {
            handler.showDonorDetails(v, getAdapterPosition());
        }
        public interface MyClickHandler
        {
            void showDonorDetails(View v, int pos);
        }
    }
    @Override
    public DonorCustomAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View customView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.donor_row, parent, false);
        ViewHolder vh=new ViewHolder(customView, new ViewHolder.MyClickHandler() {
            @Override
            public void showDonorDetails(View v, int pos) {
                Log.v(MyUtils.TAG, "showDonorDetails");
                mContext.startActivity(new Intent(mContext, DonorDetailActivity.class)
                        .putExtra(MyUtils.PARCELABEL_KEY, donors.get(pos)));
            }
        });
        return vh;
    }

    @Override
    public void onBindViewHolder(DonorCustomAdapter.ViewHolder holder, int position) {
        MyDonors donor=donors.get(position);
        holder.nameText.setText(donor.getName());
        holder.contactText.setText(donor.getContact());
        boolean sign=donor.isSign();
        String blood=donor.getBloodGroup();
        if (sign)
            blood=blood+"-";
        else
            blood=blood+"+";
        holder.bloodText.setText(blood);
    }

    @Override
    public int getItemCount() {
        return donors.size();
    }


}
