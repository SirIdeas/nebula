package com.sirideas.nebulaapp.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.sirideas.nebulaapp.R;
import com.sirideas.nebulaapp.utils.FragmentBase;

/**
 * Created by Alex on 13-12-2016.
 */

public class FragmentInicio extends FragmentBase {

    public final static String FRAGMENT_TITLE = "Inicio";

    @Override
    public String getTitle() {
        return FRAGMENT_TITLE;
    }

    public FragmentInicio() {
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_inicio, container, false);

        return view;
    }
}
