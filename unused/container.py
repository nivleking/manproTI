matrix = [
    # bay 1 dry
    [#[0][2][1]
        [0,0,3],
        [4,1,6],
        [7,8,9]
    ],
    # bay 2 reef
    [
        [0,0,0],
        [0,0,0],
        [0,0,0]
    ],
    # bay 3 reef
    [   
        [0,0,0],
        [0,0,0],
        [0,0,0]
    ],
    # bay 4 dry
    [
        [0,0,0],
        [0,0,0],
        [0,0,0]
    ],
    # bay 5 dry
    [
        [0,0,0],
        [0,0,0],
        [0,0,0]
    ]
]

temp = []
# denda = 0
biaya = 0

def checkAtas(matrix, i, j, k):
    if (i < 0) or (j < 0) or (k < 0) or (len(matrix)<=i) or (len(matrix[i])<=j) or (len(matrix[i][j]) <= k):
        return False
    else:
        if (matrix[i][j][k] != 0):
            if (j-1 == -1):
                print(matrix[i][j][k], "bisa di bongkar")
                return True
        
            if matrix[i][j-1][k] == 0:
                print(matrix[i][j-1][k], "bisa di bongkar")
                return True
            else:
                print("Ada container di atasnya")
                return False
        else:
            print("Kosong")
            return

def checkSpace(matrix,i,j,k):
    if (i < 0) or (j < 0) or (k < 0) or (len(matrix)<=i) or (len(matrix[i])<=j) or (len(matrix[i][j]) <= k):
        print()
        return False
    else:
        if matrix[i][j][k] == 0:
            if ((j < len(matrix[i]) - 1) and matrix[i][j+1][k] == 0):
                print("Melayang")
                return False
            # if (j > len(matrix[i][j])):
            #     print("Batas bawah")
            #     return False
            print("Berhasil")
            return True
        print("Sudah ada kontainer")
        return False

while (True):
    print("STATE KAPAL")
    
    print(f"Biaya: {biaya}")
    for i in range(len(matrix)):
        print(f"BAY {i+1}")
        for j in range(len(matrix[i])):
            for k in range(len(matrix[i][j])):
                print(matrix[i][j][k], end = " ")
            print()
        print()
    
    print("===MENU SIMPLE GAME===")
    print("1. Bongkar")
    print("2. Susun")
    print("3. Keluar")
    
    if (len(temp) != 0):
        print("KONTAINER YANG DAPAT DISUSUN")
        for i in temp:
            print(i, end= " ")
        print()
    
    pilihan = int(input("Pilihan: "))
    
    if pilihan == 3:
        exit(0)
        
    elif pilihan == 1:
        print("Bongkar")
        bay = int(input("Input bay berapa: "))
        baris = int(input("Input baris berapa: "))
        kolom = int(input("Input kolom berapa: "))
        
        if (checkAtas(matrix,bay,baris,kolom)):    
            temp.append(matrix[bay][baris][kolom])
            biaya+=10
            matrix[bay][baris][kolom] = 0
        
    elif pilihan == 2:
        print("Susun")
        if (len(temp) != 0):
            bay = int(input("Input bay berapa: "))
            baris = int(input("Input baris berapa: "))
            kolom = int(input("Input kolom berapa: "))
            if checkSpace(matrix,bay,baris,kolom):
                while (True):
                    print(len(temp))
                    container = int(input("Input container apa: "))
                    if container in temp and len(temp) != 0:
                        biaya+=50
                        matrix[bay][baris][kolom] = container
                        temp.remove(container)
                        break
                    print("Pilih container yang terdapat dalam temp")
                    break
        else:
            print("Bongkar container dulu / terima sales call")
    print()

# matrix[0][1][1] -> 5
# bongkar
# 1 -> Rp1000.0000
# 2 -> Rp1000.0000
# 5 -> Rp1000.0000
# 8 -> Rp1000.0000
# 4jt

# [1,2,5] -> denda karna tidak disusun

# susun
# 8 -> Rp2.000.000
# 2jt 

# 6jt