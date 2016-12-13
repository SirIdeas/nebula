package com.sirideas.nebulaapp.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.sirideas.nebulaapp.R;
import com.sirideas.nebulaapp.utils.FragmentBase;

/**
 * Created by Alex on 12-12-2016.
 */

public class FragmentMessages extends FragmentBase {

    public final static String FRAGMENT_TITLE = "Messages";

    public String getTitle() {
        return FRAGMENT_TITLE;
    }

    public FragmentMessages() {
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_messages, container, false);

        return view;
    }
}
