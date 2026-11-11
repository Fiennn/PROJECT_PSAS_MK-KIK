package com.homestaygo.app

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.homestaygo.app.databinding.ItemHomestayBinding

class HomestayAdapter(
    private val onClick: (Homestay) -> Unit
) : RecyclerView.Adapter<HomestayAdapter.ViewHolder>() {

    private val items = mutableListOf<Homestay>()

    fun submitList(newItems: List<Homestay>) {
        items.clear()
        items.addAll(newItems)
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val binding = ItemHomestayBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(items[position])
    }

    override fun getItemCount(): Int = items.size

    inner class ViewHolder(private val binding: ItemHomestayBinding) : RecyclerView.ViewHolder(binding.root) {
        fun bind(homestay: Homestay) {
            binding.nameText.text = homestay.nama
            binding.addressText.text = homestay.alamat
            binding.roomCountText.text = "${homestay.jumlahKamar} kamar"
            binding.root.setOnClickListener { onClick(homestay) }
        }
    }
}
