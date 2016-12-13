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
public class FragmentLedBlink extends FragmentBase {

    public final static String FRAGMENT_TITLE = "Led Blink";

    public String getTitle() {
        return FRAGMENT_TITLE;
    }

    public FragmentLedBlink() {
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_led_blink, container, false);

        return view;
    }
}
