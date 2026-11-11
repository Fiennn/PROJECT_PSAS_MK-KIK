package com.homestaygo.app

import com.google.gson.annotations.SerializedName

data class HomestayResponse(
    val success: Boolean,
    val data: List<Homestay>?
)

data class Homestay(
    val id: Int,
    val nama: String,
    val alamat: String,
    val deskripsi: String?,
    val fasilitas: List<String>?,
    @SerializedName("jumlah_kamar")
    val jumlahKamar: Int
)
