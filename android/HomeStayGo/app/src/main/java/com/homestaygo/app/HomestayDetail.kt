package com.homestaygo.app

import com.google.gson.annotations.SerializedName

data class HomestayDetailResponse(
    val success: Boolean,
    val data: HomestayDetail?
)

data class HomestayDetail(
    val id: Int,
    val nama: String,
    val alamat: String,
    val deskripsi: String?,
    val fasilitas: List<String>?,
    val kamar: List<Room>?,
    val paket: List<Package>?
)

data class Room(
    val id: Int,
    val nama: String,
    val kapasitas: Int,
    @SerializedName("harga_per_malam")
    val hargaPerMalam: Double,
    val status: String
)

data class Package(
    val id: Int,
    val nama: String,
    val deskripsi: String?,
    val harga: Double,
    val durasi: String?
)
