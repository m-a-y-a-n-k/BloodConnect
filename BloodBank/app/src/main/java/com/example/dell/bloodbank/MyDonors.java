package com.example.dell.bloodbank;

import android.os.Parcel;
import android.os.Parcelable;

public class MyDonors implements Parcelable {
    int age, id;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    String name, contact, address, gender, bloodGroup, city, country, email;
    boolean sign;//1 for -ve and 0 for +ve

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public int getAge() {
        return age;
    }

    public void setAge(int age) {
        this.age = age;
    }

    public String getBloodGroup() {
        return bloodGroup;
    }

    public void setBloodGroup(String bloodGroup) {
        this.bloodGroup = bloodGroup;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getContact() {
        return contact;
    }

    public void setContact(String contact) {
        this.contact = contact;
    }

    public String getCountry() {
        return country;
    }

    public void setCountry(String country) {
        this.country = country;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getGender() {
        return gender;
    }

    public void setGender(String gender) {
        this.gender = gender;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public boolean isSign() {
        return sign;
    }

    public void setSign(boolean sign) {
        this.sign = sign;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(this.id);
        dest.writeInt(this.age);
        dest.writeString(this.name);
        dest.writeString(this.contact);
        dest.writeString(this.address);
        dest.writeString(this.gender);
        dest.writeString(this.bloodGroup);
        dest.writeString(this.city);
        dest.writeString(this.country);
        dest.writeString(this.email);
        dest.writeByte(this.sign ? (byte) 1 : (byte) 0);
    }

    public MyDonors() {
    }

    protected MyDonors(Parcel in) {
        this.id = in.readInt();
        this.age = in.readInt();
        this.name = in.readString();
        this.contact = in.readString();
        this.address = in.readString();
        this.gender = in.readString();
        this.bloodGroup = in.readString();
        this.city = in.readString();
        this.country = in.readString();
        this.email = in.readString();
        this.sign = in.readByte() != 0;
    }

    public static final Parcelable.Creator<MyDonors> CREATOR = new Parcelable.Creator<MyDonors>() {
        @Override
        public MyDonors createFromParcel(Parcel source) {
            return new MyDonors(source);
        }

        @Override
        public MyDonors[] newArray(int size) {
            return new MyDonors[size];
        }
    };
}
