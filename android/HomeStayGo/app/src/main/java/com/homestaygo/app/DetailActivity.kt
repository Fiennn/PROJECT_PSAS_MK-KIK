package com.homestaygo.app

import android.os.Bundle
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.homestaygo.app.databinding.ActivityDetailBinding

class DetailActivity : AppCompatActivity() {

    companion object {
        const val EXTRA_HOMESTAY_ID = "extra_homestay_id"
        const val EXTRA_HOMESTAY_NAME = "extra_homestay_name"
    }

    private lateinit var binding: ActivityDetailBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityDetailBinding.inflate(layoutInflater)
        setContentView(binding.root)

        val id = intent.getIntExtra(EXTRA_HOMESTAY_ID, -1)
        val name = intent.getStringExtra(EXTRA_HOMESTAY_NAME).orEmpty()

        binding.titleText.text = name
        binding.idText.text = "ID Homestay: $id"
        binding.infoText.text = "Detail lengkap homestay akan dimuat dari API pada pengembangan berikutnya."
    }
}
