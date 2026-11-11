package com.homestaygo.app

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.homestaygo.app.databinding.ActivityMainBinding
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding
    private lateinit var adapter: HomestayAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        adapter = HomestayAdapter { homestay ->
            val intent = Intent(this, DetailActivity::class.java)
            intent.putExtra(DetailActivity.EXTRA_HOMESTAY_ID, homestay.id)
            intent.putExtra(DetailActivity.EXTRA_HOMESTAY_NAME, homestay.nama)
            startActivity(intent)
        }

        binding.recyclerView.layoutManager = LinearLayoutManager(this)
        binding.recyclerView.adapter = adapter

        loadHomestays()
    }

    private fun loadHomestays() {
        binding.progressBar.visibility = View.VISIBLE
        binding.errorText.visibility = View.GONE

        ApiClient.service.getHomestays().enqueue(object : Callback<HomestayResponse> {
            override fun onResponse(call: Call<HomestayResponse>, response: Response<HomestayResponse>) {
                binding.progressBar.visibility = View.GONE

                if (response.isSuccessful) {
                    val data = response.body()?.data.orEmpty()
                    adapter.submitList(data)

                    if (data.isEmpty()) {
                        binding.errorText.text = getString(R.string.empty_homestay)
                        binding.errorText.visibility = View.VISIBLE
                    }
                } else {
                    showError("Gagal memuat data: ${response.code()}")
                }
            }

            override fun onFailure(call: Call<HomestayResponse>, t: Throwable) {
                binding.progressBar.visibility = View.GONE
                showError("Tidak dapat terhubung ke server: ${t.localizedMessage}")
            }
        })
    }

    private fun showError(message: String) {
        binding.errorText.text = message
        binding.errorText.visibility = View.VISIBLE
        Toast.makeText(this, message, Toast.LENGTH_LONG).show()
    }
}
